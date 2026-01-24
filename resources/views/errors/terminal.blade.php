<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, viewport-fit=cover, initial-scale=1, minimal-ui, maximum-scale=1, user-scalable=no">
    <title>@yield('title') - Daniel Schmier</title>
    
    <meta name="theme-color" content="#f2f0ef" media="(prefers-color-scheme: light)" id="theme-color-light">
    <meta name="theme-color" content="#040e0c" media="(prefers-color-scheme: dark)" id="theme-color-dark">
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://stats.captainscor.ch" crossorigin>
    
    <link rel="manifest" href="/img/favicons/site.webmanifest" />
    <link rel="icon" type="image/png" href="/img/favicons/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/img/favicons/favicon.svg" />
    <link rel="shortcut icon" href="/img/favicons/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicons/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="Daniel Schmier – Design Engineer" />

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=work-sans:300,400,500,700" rel="stylesheet" />

    @vite(['resources/css/app.css'])
</head>
<body class="antialiased font-mono min-h-screen flex items-center justify-center p-4 overflow-hidden selection:bg-teal-black selection:text-off-white dark:selection:bg-off-white dark:selection:text-teal-black bg-background bg-[linear-gradient(160deg,_#f2f0ef_0%,_rgb(242,240,239)_50%,_rgb(229_228_228)_80%,_rgb(42_177_147_/_100%)_100%)] dark:bg-[linear-gradient(160deg,_#040e0c_0%,_rgb(1_2_2)_50%,_rgb(9_28_25)_80%,_rgb(42_177_147_/_35%)_100%)] bg-no-repeat bg-cover bg-center">
    
    <main id="terminal-window" class="relative z-10 w-full max-w-4xl shadow-2xl rounded-xl overflow-hidden border border-[#115e59] scorch-theme transition-shadow duration-300 hover:shadow-[0_0_60px_-15px_rgba(42,177,147,0.3)] flex flex-col min-h-[330px] max-h-[85vh] h-auto">
        
        <!-- Window Header (Draggable Handle) -->
        <div id="terminal-header" class="bg-[#0a1a18] px-4 py-3 relative flex items-center justify-end md:justify-center border-b border-[#115e59]/40 cursor-grab active:cursor-grabbing select-none">
            <!-- Window Controls (Left) -->
            <div class="absolute left-4 flex items-center space-x-2 group">
                <button onclick="window.location.href='/'" class="h-3 w-3 flex items-center justify-center rounded-full bg-[#ff5f56] text-[8px] font-bold text-black/50 opacity-100 transition-all hover:bg-[#ff5f56]/80" title="Close">
                    <span class="opacity-0 group-hover:opacity-100">✕</span>
                </button>
                <div class="h-3 w-3 flex items-center justify-center rounded-full bg-[#ffbd2e] text-[8px] font-bold text-black/50 opacity-100 transition-all hover:bg-[#ffbd2e]/80" title="Minimize">
                    <span class="opacity-0 group-hover:opacity-100">−</span>
                </div>
                <button onclick="toggleFullscreen()" class="h-3 w-3 flex items-center justify-center rounded-full bg-[#27c93f] text-[6px] font-bold text-black/50 opacity-100 transition-all hover:bg-[#27c93f]/80" title="Toggle Fullscreen">
                    <span class="opacity-0 group-hover:opacity-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-2 h-2 fill-current"><path d="M32 32C14.3 32 0 46.3 0 64v96c0 17.7 14.3 32 32 32s32-14.3 32-32V96h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H32zM64 352c0-17.7-14.3-32-32-32S0 334.3 0 352v96c0 17.7 14.3 32 32 32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H64V352zM320 32c-17.7 0-32 14.3-32 32s14.3 32 32 32h64v64c0 17.7 14.3 32 32 32s32-14.3 32-32V64c0-17.7-14.3-32-32-32H320zM448 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v64H320c-17.7 0-32 14.3-32 32s14.3 32 32 32h96c17.7 0 32-14.3 32-32V352z"/></svg>
                    </span>
                </button>
            </div>
            
            <!-- Title (Centered) -->
            <div class="flex items-center gap-2 pl-2 opacity-60 md:pl-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-folder-open text-[#ccfbf1]"><path d="m6 14 1.45-2.9A2 2 0 0 1 9.24 10H20a2 2 0 0 1 1.94 2.5l-1.55 6a2 2 0 0 1-1.94 1.5H4a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2h3.93a2 2 0 0 1 1.66.9l.82 1.2a2 2 0 0 0 1.66.9H18a2 2 0 0 1 2 2v2"/></svg>
                <span class="text-sm font-medium text-[#ccfbf1]"
                    ><span class="hidden md:inline">user &mdash; </span>user@scorchOS &mdash; -zsh &mdash; 80x24</span
                >
            </div>
        </div>

        <!-- Terminal Content -->
        <div id="terminal-body" class="p-6 md:p-8 text-sm md:text-base font-mono leading-relaxed relative flex-1 overflow-y-auto terminal-scrollbar" onclick="focusInput()">
            <div class="scanline"></div>
            
            <!-- Last Login -->
            <div class="mb-6 text-[#64748b]">Last login: {{ now()->format('D M d H:i:s') }} on ttys008</div>

            <!-- Initial Command Block -->
            <div id="initial-command" class="mb-4 hidden">
                <div class="flex flex-wrap gap-x-3 gap-y-1 mb-2">
                    <span class="text-[#2ab193] font-bold">➜</span>
                    <span class="text-[#2ab193] font-bold">~/scorchOS</span>
                    <span class="text-[#64748b]">$</span>
                    <span class="text-[#a78bfa] font-bold">cat</span>
                    <span class="text-[#ccfbf1]">resources/views/errors/@yield('code').blade.php</span>
                </div>
            </div>

            <!-- Error Output Block (Initially Hidden for Typing Effect) -->
            <div id="error-output" class="space-y-4 mb-8 pl-2 border-l-2 border-[#115e59]/50 ml-1 hidden">
                <div class="flex gap-2 text-[#ff5f56]">
                    <span>[ERROR]</span>
                    <span class="font-bold">@yield('code') @yield('message')</span>
                </div>
                
                <div class="text-[#ccfbf1] opacity-90">
                    <p>@yield('description')</p>
                </div>

                <!-- Custom Terminal Output from View -->
                <div class="text-[#2ab193] text-xs font-mono opacity-80 mt-4">
                    @yield('terminal')
                </div>
            </div>


            <!-- Suggested Actions (Appears after error) -->
            <div id="suggested-actions" class="mt-8 pt-6 border-t border-[#115e59]/30 hidden">
                <div class="mb-3 text-[#64748b] italic"># Suggested actions:</div>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ url('/') }}" class="group flex items-center gap-2 text-[#60a5fa] hover:text-[#2ab193] transition-colors hover:cursor-pointer">
                        <span class="text-[#2ab193]">></span>
                        <span class="underline decoration-transparent group-hover:decoration-current transition-all">cd /home</span>
                    </a>
                    <button onclick="window.location.reload()" class="group flex items-center gap-2 text-[#60a5fa] hover:text-[#2ab193] transition-colors hover:cursor-pointer">
                        <span class="text-[#2ab193]">></span>
                        <span class="underline decoration-transparent group-hover:decoration-current transition-all">sudo retry --force</span>
                    </button>
                    @yield('actions')
                </div>
            </div>

            <!-- Dynamic Output Container -->
            <div id="output-container"></div>

            <!-- Interactive Cursor Line -->
            <div class="mt-2 flex gap-2 items-center" id="input-line" style="display: none;">
                <span class="text-[#2ab193] font-bold">➜</span>
                <span class="text-[#2ab193] font-bold">~/scorchOS</span>
                <span class="text-[#64748b]">$</span>
                <input type="text" id="terminal-input" class="bg-transparent border-none outline-none text-[#ccfbf1] flex-1 font-mono p-0 m-0 focus:ring-0 placeholder-[#64748b]/50" autocomplete="off" spellcheck="false">
            </div>


        </div>

        <!-- Bottom Bar -->
        <div class="bg-[#0a1a18] border-t border-[#115e59]/30 px-4 py-2 flex items-center justify-between text-[10px] text-[#64748b] select-none">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-git-branch"><line x1="6" x2="6" y1="3" y2="15"/><circle cx="18" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M18 9a9 9 0 0 1-9 9"/></svg>
                    <span>main</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hash"><line x1="4" x2="20" y1="9" y2="9"/><line x1="4" x2="20" y1="15" y2="15"/><line x1="10" x2="8" y1="3" y2="21"/><line x1="16" x2="14" y1="3" y2="21"/></svg>
                    <span>31ab968</span>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#27c93f]"></div>
                    <span>Online</span>
                </div>
                <div>UTF-8</div>
                <div>Blade</div>
            </div>
        </div>
    </main>

    <script>
        class Terminal {
            constructor() {
                this.input = document.getElementById('terminal-input');
                this.outputContainer = document.getElementById('output-container');
                this.history = [];
                this.historyIndex = -1;
                this.fileSystem = {
                    '~': ['scorchOS', 'Documents', 'Downloads', 'Projects'],
                    '~/scorchOS': ['app', 'bootstrap', 'config', 'database', 'public', 'resources', 'routes', 'storage', 'tests', 'vendor', '.env', 'artisan', 'composer.json', 'package.json', 'phpunit.xml', 'README.md', 'vite.config.js'],
                    '~/Projects': ['lyftd', 'way.food', 'loz'],
                };
                this.currentDir = '~/scorchOS';
                
                this.init();
            }

            init() {
                this.setupDrag();
                this.setupFullscreen();
                this.setupInput();
                this.runStartupSequence();
            }

            async runStartupSequence() {
                const initialCommand = document.getElementById('initial-command');
                const errorOutput = document.getElementById('error-output');
                const suggestedActions = document.getElementById('suggested-actions');
                const inputLine = document.getElementById('input-line');

                await this.wait(500);
                initialCommand.classList.remove('hidden');
                
                await this.wait(300);
                errorOutput.classList.remove('hidden');
                
                await this.wait(100);
                suggestedActions.classList.remove('hidden');
                
                inputLine.style.display = 'flex';
                this.input.focus();
            }

            wait(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            }

            setupInput() {
                this.input.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        this.handleCommand(this.input.value);
                        this.input.value = '';
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        this.navigateHistory('up');
                    } else if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        this.navigateHistory('down');
                    }
                });

                document.getElementById('terminal-body').addEventListener('click', () => {
                    if (!window.getSelection().toString()) {
                        this.input.focus();
                    }
                });
            }

            handleCommand(cmd) {
                const command = cmd.trim();
                if (!command) return;

                this.history.push(command);
                this.historyIndex = this.history.length;

                this.printCommand(command);

                const parts = command.split(' ');
                const baseCmd = parts[0].toLowerCase();
                const args = parts.slice(1);

                switch (baseCmd) {
                    case 'help':
                        this.printOutput(`
<span class="text-[#2ab193]">Available commands:</span>
  <span class="text-[#60a5fa]">cd</span>       Change directory
  <span class="text-[#60a5fa]">ls</span>       List directory contents
  <span class="text-[#60a5fa]">clear</span>    Clear the terminal screen
  <span class="text-[#60a5fa]">pwd</span>      Print working directory
  <span class="text-[#60a5fa]">whoami</span>   Print current user
  <span class="text-[#60a5fa]">cat</span>      Concatenate and print files
  <span class="text-[#60a5fa]">sudo</span>     Execute a command as another user
                        `);
                        break;
                    case 'clear':
                        this.outputContainer.innerHTML = '';
                        document.getElementById('initial-command').classList.add('hidden');
                        document.getElementById('error-output').classList.add('hidden');
                        document.getElementById('suggested-actions').classList.add('hidden');
                        break;
                    case 'ls':
                        const files = this.fileSystem[this.currentDir] || [];
                        const formattedFiles = files.map(f => {
                            if (f.includes('.')) return `<span class="text-[#ccfbf1]">${f}</span>`;
                            return `<span class="text-[#60a5fa] font-bold">${f}/</span>`;
                        }).join('  ');
                        this.printOutput(formattedFiles);
                        break;
                    case 'pwd':
                        this.printOutput(this.currentDir);
                        break;
                    case 'whoami':
                        this.printOutput('user');
                        break;
                    case 'cd':
                        this.handleCd(args[0]);
                        break;
                    case 'sudo':
                        if (args[0] === 'retry') {
                            window.location.reload();
                        } else {
                            this.printOutput(`<span class="text-[#ff5f56]">Password required for sudo. Access denied.</span>`);
                        }
                        break;
                    case 'exit':
                        window.location.href = '/';
                        break;
                    default:
                        this.printOutput(`<span class="text-[#ff5f56]">zsh: command not found: ${baseCmd}</span>`);
                }

                const terminalBody = document.getElementById('terminal-body');
                terminalBody.scrollTop = terminalBody.scrollHeight;
            }

            handleCd(path) {
                if (!path || path === '~') {
                    this.currentDir = '~';
                    window.location.href = '/';
                    return;
                }
                
                if (path === '..') {
                    if (this.currentDir === '~/scorchOS') this.currentDir = '~';
                    else if (this.currentDir === '~') this.printOutput('Already at root level for this session.');
                    return;
                }

                if (path.startsWith('/')) {
                    if (path === '/home') window.location.href = '/';
                    else if (path === '/about') window.location.href = '/about';
                    else if (path === '/portfolio') window.location.href = '/portfolio';
                    else this.printOutput(`<span class="text-[#ff5f56]">cd: no such file or directory: ${path}</span>`);
                    return;
                }

                const target = this.currentDir === '~' ? path : `${this.currentDir}/${path}`;
                if (this.fileSystem[this.currentDir] && this.fileSystem[this.currentDir].includes(path.replace('/', ''))) {
                     this.printOutput(`<span class="text-[#64748b]">cd: ${path}: permission denied (simulated)</span>`);
                } else {
                    this.printOutput(`<span class="text-[#ff5f56]">cd: no such file or directory: ${path}</span>`);
                }
            }

            printCommand(cmd) {
                const div = document.createElement('div');
                div.className = 'flex flex-wrap gap-x-3 gap-y-1 mb-1';
                div.innerHTML = `
                    <span class="text-[#2ab193] font-bold">➜</span>
                    <span class="text-[#2ab193] font-bold">${this.currentDir}</span>
                    <span class="text-[#64748b]">$</span>
                    <span class="text-[#ccfbf1]">${this.escapeHtml(cmd)}</span>
                `;
                this.outputContainer.appendChild(div);
            }

            printOutput(html) {
                const div = document.createElement('div');
                div.className = 'mb-4 text-[#ccfbf1] opacity-90 whitespace-pre-wrap';
                div.innerHTML = html;
                this.outputContainer.appendChild(div);
            }

            navigateHistory(direction) {
                if (this.history.length === 0) return;

                if (direction === 'up') {
                    this.historyIndex = Math.max(0, this.historyIndex - 1);
                } else {
                    this.historyIndex = Math.min(this.history.length, this.historyIndex + 1);
                }

                if (this.historyIndex < this.history.length) {
                    this.input.value = this.history[this.historyIndex];
                } else {
                    this.input.value = '';
                }
                
                setTimeout(() => {
                    this.input.selectionStart = this.input.selectionEnd = this.input.value.length;
                }, 0);
            }

            escapeHtml(text) {
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, function(m) { return map[m]; });
            }

            setupDrag() {
                const terminal = document.getElementById('terminal-window');
                const header = document.getElementById('terminal-header');
                
                let isDragging = false;
                let currentX, currentY, initialX, initialY;
                let xOffset = 0, yOffset = 0;

                header.addEventListener("mousedown", dragStart);
                document.addEventListener("mouseup", dragEnd);
                document.addEventListener("mousemove", drag);

                function dragStart(e) {
                    initialX = e.clientX - xOffset;
                    initialY = e.clientY - yOffset;

                    if (e.target === header || header.contains(e.target)) {
                        if (e.target.tagName === 'BUTTON' || e.target.parentElement.tagName === 'BUTTON') return;
                        isDragging = true;
                    }
                }

                function dragEnd(e) {
                    initialX = currentX;
                    initialY = currentY;
                    isDragging = false;
                }

                function drag(e) {
                    if (isDragging) {
                        e.preventDefault();
                        currentX = e.clientX - initialX;
                        currentY = e.clientY - initialY;
                        xOffset = currentX;
                        yOffset = currentY;
                        terminal.style.transform = `translate3d(${currentX}px, ${currentY}px, 0)`;
                    }
                }
            }

            setupFullscreen() {
                window.toggleFullscreen = () => {
                    const terminal = document.getElementById('terminal-window');
                    if (!document.fullscreenElement) {
                        terminal.requestFullscreen().catch(err => {
                            console.log(`Error attempting to enable fullscreen: ${err.message}`);
                        });
                    } else {
                        document.exitFullscreen();
                    }
                };
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            new Terminal();
        });

        function focusInput() {
            document.getElementById('terminal-input').focus();
        }
    </script>
</body>
</html>
