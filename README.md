# 🔐 PHP Backdoor Collection

**Educational & Security Research Only**

## ⚠️ LEGAL DISCLAIMER

This repository contains PHP backdoors and webshells for **EDUCATIONAL, SECURITY RESEARCH, AND AUTHORIZED TESTING PURPOSES ONLY**.

## ✅ Authorized Use

**You MAY use this if:**
- ✅ You own the system being tested
- ✅ You have **written permission** from the owner
- ✅ You're conducting authorized penetration testing
- ✅ You're learning in an isolated lab environment
- ✅ You're developing security detection tools

**You MUST NOT:**
- ❌ Test systems without explicit authorization
- ❌ Use for malicious purposes
- ❌ Upload to public servers without permission
- ❌ Access unauthorized systems
- ❌ Cause harm or steal data

## 🎓 Purpose

**Learn About:**
- How backdoors work (to defend against them)
- Detection techniques and patterns
- Malware analysis and forensics
- Incident response procedures
- Security hardening methods

**Use For:**
- Red team exercises (authorized)
- Blue team training
- Academic education
- Developing IDS/IPS signatures
- Security tool development
- Malware research in sandboxes

## 🛡️ Defensive Applications

### Detection Patterns
Common backdoor functions to monitor:
```php
eval(), base64_decode(), gzinflate(), str_rot13()
system(), exec(), shell_exec(), passthru()
assert(), create_function(), call_user_func()
```

### Tools for Detection
- Linux Malware Detect (LMD)
- ClamAV
- PHP Malware Finder
- ModSecurity (WAF)
- File Integrity Monitoring

### Safe Testing
- Isolated VM or Docker container
- No internet connection
- Host-only network
- Never on production systems

## 🚨 If You Find a Backdoor

**On Your Site:**
1. Take offline if critical
2. Document everything
3. Clean and restore from backup
4. Patch vulnerabilities
5. Change all passwords

**During Authorized Testing:**
1. Stop testing immediately
2. Document the finding
3. Report to client per rules of engagement
4. Do NOT use the backdoor

**Responsible Disclosure:**
1. Report to vendor privately
2. Give 90 days to patch
3. Do NOT exploit or share publicly
4. Help, don't harm

## 🤝 Contributing

Welcome contributions that:
- ✅ Add educational value
- ✅ Improve detection methods
- ✅ Enhance documentation
- ✅ Share defense strategies

Do NOT contribute:
- ❌ Zero-days without vendor notification
- ❌ Live exploits for active systems
- ❌ Stolen credentials

## ⚖️ Terms of Use

**By using this repository, you agree:**
1. To use only for authorized, legal, ethical purposes
2. To obtain written permission before testing
3. To follow all applicable laws
4. To accept full responsibility for your actions
5. That unauthorized use may result in prosecution

**Authors/maintainers:**
- Provide this for educational purposes only
- Are not responsible for misuse
- Do not condone illegal activity
- May report illegal use to authorities

## 💡 Remember

**"With great power comes great responsibility."**

Use this knowledge to:
- 🎩 **Defend** - Secure systems and protect users
- 🎓 **Educate** - Share knowledge responsibly
- 🛡️ **Improve** - Make the internet safer

**Not to:**
- ⚫ **Attack** - Unauthorized access
- 💰 **Profit** - Exploit vulnerabilities
- 😈 **Harm** - Cause damage or steal data

**If you're unsure whether something is legal - DON'T DO IT.**

**Stay Ethical. Stay Legal. Stay Safe.** 🛡️

*For educational and defensive security purposes only.*
