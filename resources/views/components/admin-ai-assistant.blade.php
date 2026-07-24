<div id="admin-ai-assistant" aria-live="polite">
    <button type="button" id="admin-ai-toggle" class="admin-ai-launcher" aria-label="Open GAFALBUM AI Assistant">
        <span class="admin-ai-launcher-glow"></span>
        <span class="admin-ai-launcher-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M12 4.5v2M12 17.5v2M4.5 12h2M17.5 12h2M7.05 7.05l1.42 1.42M15.53 15.53l1.42 1.42M16.95 7.05l-1.42 1.42M8.47 15.53l-1.42 1.42" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M9 12a3 3 0 1 0 6 0 3 3 0 0 0-6 0Z" stroke="currentColor" stroke-width="1.8"/>
            </svg>
        </span>
        <span class="admin-ai-launcher-copy">
            <strong>AI</strong>
            <small>Ask</small>
        </span>
    </button>

    <section id="admin-ai-window" class="admin-ai-panel hidden" aria-label="GAFALBUM AI Assistant">
        <header class="admin-ai-header">
            <div class="admin-ai-header-mark" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M8 5.5c1.6 1.7 1.6 3.8 0 5.5s-1.6 3.8 0 5.5M12 4c1.9 2 1.9 4.5 0 6.5s-1.9 4.5 0 6.5M16 5.5c1.6 1.7 1.6 3.8 0 5.5s-1.6 3.8 0 5.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="admin-ai-title">
                <p>GAFALBUM AI</p>
                <span>Gallery operations assistant</span>
            </div>
            <button type="button" id="admin-ai-close" class="admin-ai-icon-btn" aria-label="Close GAFALBUM AI Assistant">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>
                </svg>
            </button>
        </header>

        <div class="admin-ai-hero">
            <div>
                <span class="admin-ai-status"><i></i> Live admin context</span>
                <h3>Ask about events, payments, users, and hero images.</h3>
            </div>
        </div>

        <div class="admin-ai-quick-actions" aria-label="Suggested prompts">
            <button type="button" data-ai-prompt="Give me a quick admin summary for the system today.">Ops summary</button>
            <button type="button" data-ai-prompt="Which events are selling best and what should I feature next?">Top events</button>
            <button type="button" data-ai-prompt="Summarize draft, published, and archived event counts.">Event status</button>
        </div>

        <div id="admin-ai-history" class="admin-ai-history">
            <div class="admin-ai-message admin-ai-message-bot">
                <div class="admin-ai-avatar">AI</div>
                <div>
                    <div class="admin-ai-bubble">
                        Hi. I can read the current admin context and help with event publishing, payments, downloads, users, and uploaded gallery images.
                    </div>
                    <span class="admin-ai-meta">GAFALBUM AI · ready</span>
                </div>
            </div>
        </div>

        <footer class="admin-ai-composer">
            <div id="admin-ai-file-preview" class="admin-ai-file-preview hidden">
                <div class="admin-ai-file-meta">
                    <span aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M6.75 3.75h6.5L18 8.5v11.75H6.75V3.75Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            <path d="M13 4v5h5" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <p id="admin-ai-file-name"></p>
                </div>
                <button type="button" id="admin-ai-remove-file" class="admin-ai-small-btn" aria-label="Remove selected file">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <div class="admin-ai-input-row">
                <label for="admin-ai-file" class="admin-ai-attach-btn" aria-label="Attach image">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M21.44 11.05 12.25 20.24a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 1 1 5.66 5.66l-9.2 9.19a2 2 0 1 1-2.82-2.82l8.48-8.49" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <input id="admin-ai-file" class="hidden" type="file" accept="image/jpeg,image/png,image/webp">
                </label>

                <input id="admin-ai-input" type="text" placeholder="Ask the admin assistant..." autocomplete="off">

                <button type="button" id="admin-ai-speak-toggle" class="admin-ai-speak-btn is-on" aria-label="Toggle voice replies" title="Voice replies on">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M5 9.5v5h3.5L13 18V6L8.5 9.5H5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                        <path d="M16 9a4 4 0 0 1 0 6M18.5 6.5a7.5 7.5 0 0 1 0 11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </button>

                <button type="button" id="admin-ai-voice" class="admin-ai-voice-btn" aria-label="Ask with voice">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 14.5a3 3 0 0 0 3-3v-5a3 3 0 1 0-6 0v5a3 3 0 0 0 3 3Z" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M5.5 11.5a6.5 6.5 0 0 0 13 0M12 18v3M9 21h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </button>

                <button type="button" id="admin-ai-send" class="admin-ai-send-btn" aria-label="Send message">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </footer>
    </section>
</div>

<style>
    #admin-ai-assistant { --ai-red:#800000; --ai-red-soft:#a63a3a; --ai-red-light:#f4d6d6; --ai-gold:#d6ad45; --ai-ink:#211315; --ai-muted:#7b6468; position:fixed; right:1.5rem; bottom:1.5rem; z-index:99999; font-family:ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif; pointer-events:none; }
    #admin-ai-assistant *,#admin-ai-assistant *::before,#admin-ai-assistant *::after{box-sizing:border-box;}
    #admin-ai-assistant button,#admin-ai-assistant label,#admin-ai-window{pointer-events:auto;}
    #admin-ai-assistant svg{width:1.25rem;height:1.25rem;display:block;}
    #admin-ai-assistant .hidden{display:none !important;}
    .admin-ai-launcher{position:relative;display:grid;grid-template-columns:3.15rem auto;align-items:center;gap:.5rem;min-width:7.25rem;height:4rem;padding:.42rem .95rem .42rem .42rem;border:1px solid rgba(255,255,255,.72);border-radius:999px;background:linear-gradient(135deg,#fff8f8 0%,var(--ai-red-light) 45%,#f7e7b6 100%);color:var(--ai-ink);box-shadow:0 22px 48px rgba(128,0,0,.25),0 8px 18px rgba(15,23,42,.12);cursor:pointer;transition:transform 180ms ease,box-shadow 180ms ease;}
    .admin-ai-launcher:hover{transform:translateY(-3px);box-shadow:0 28px 56px rgba(128,0,0,.28),0 12px 24px rgba(15,23,42,.14);}
    .admin-ai-launcher-glow{position:absolute;inset:-.45rem;border-radius:inherit;background:radial-gradient(circle, rgba(128,0,0,.18), transparent 68%);animation:admin-ai-pulse 1.9s ease-out infinite;z-index:-1;}
    .admin-ai-launcher-icon{display:flex;width:3.15rem;height:3.15rem;align-items:center;justify-content:center;border-radius:999px;background:rgba(255,255,255,.78);color:var(--ai-red);box-shadow:inset 0 0 0 1px rgba(255,255,255,.7);}
    .admin-ai-launcher-copy{display:flex;flex-direction:column;align-items:flex-start;line-height:1;}
    .admin-ai-launcher-copy strong{font-size:.95rem;font-weight:800;}
    .admin-ai-launcher-copy small{margin-top:.24rem;font-size:.68rem;font-weight:700;color:rgba(33,19,21,.62);text-transform:uppercase;}
    .admin-ai-panel{position:absolute;right:0;bottom:5rem;display:grid;grid-template-rows:auto auto auto minmax(0,1fr) auto;width:min(430px,calc(100vw - 2rem));max-width:calc(100vw - 2rem);height:min(690px,calc(100vh - 7rem));overflow:hidden;border:1px solid rgba(255,255,255,.78);border-radius:1.65rem;background:radial-gradient(circle at 18% 0%, rgba(166,58,58,.18), transparent 31%),linear-gradient(180deg, rgba(255,250,250,.98) 0%, rgba(255,246,246,.98) 42%, rgba(255,255,255,.99) 100%);box-shadow:0 34px 80px rgba(15,23,42,.28),0 10px 26px rgba(128,0,0,.16);color:var(--ai-ink);}
    .admin-ai-header{display:flex;align-items:center;gap:.85rem;padding:1rem;border-bottom:1px solid rgba(214,173,69,.18);background:rgba(255,255,255,.54);backdrop-filter:blur(14px);}
    .admin-ai-header-mark{display:flex;width:2.8rem;height:2.8rem;flex:0 0 auto;align-items:center;justify-content:center;border-radius:1rem;background:linear-gradient(135deg,#fff 0%,var(--ai-red-light) 100%);color:var(--ai-red);box-shadow:0 10px 22px rgba(128,0,0,.16);}
    .admin-ai-title{min-width:0;flex:1;}
    .admin-ai-title p{margin:0;font-size:.98rem;font-weight:800;color:var(--ai-ink);}
    .admin-ai-title span{display:block;margin-top:.18rem;color:var(--ai-muted);font-size:.76rem;font-weight:650;}
    .admin-ai-icon-btn,.admin-ai-small-btn{display:inline-flex;align-items:center;justify-content:center;border:0;border-radius:999px;background:rgba(33,19,21,.06);color:rgba(33,19,21,.62);cursor:pointer;transition:background 160ms ease,color 160ms ease;}
    .admin-ai-icon-btn{width:2.35rem;height:2.35rem;}
    .admin-ai-small-btn{width:1.9rem;height:1.9rem;}
    .admin-ai-small-btn svg{width:1rem;height:1rem;}
    .admin-ai-icon-btn:hover,.admin-ai-small-btn:hover{background:rgba(128,0,0,.12);color:var(--ai-red);}
    .admin-ai-hero{padding:1rem 1rem .8rem;}
    .admin-ai-hero>div{border:1px solid rgba(214,173,69,.24);border-radius:1.2rem;padding:1rem;background:linear-gradient(135deg, rgba(255,255,255,.82), rgba(166,58,58,.1)),radial-gradient(circle at 90% 0%, rgba(214,173,69,.22), transparent 34%);}
    .admin-ai-status{display:inline-flex;align-items:center;gap:.42rem;color:#9c6f16;font-size:.68rem;font-weight:800;text-transform:uppercase;}
    .admin-ai-status i{display:block;width:.48rem;height:.48rem;border-radius:999px;background:#22c55e;box-shadow:0 0 0 4px rgba(34,197,94,.14);}
    .admin-ai-hero h3{margin:.45rem 0 0;max-width:100%;font-size:1.03rem;line-height:1.35;font-weight:780;color:#241124;}
    .admin-ai-quick-actions{display:flex;gap:.45rem;overflow-x:auto;padding:0 1rem .85rem;scrollbar-width:none;}
    .admin-ai-quick-actions::-webkit-scrollbar{display:none;}
    .admin-ai-quick-actions button{flex:0 0 auto;border:1px solid rgba(214,173,69,.22);border-radius:999px;background:rgba(255,255,255,.78);color:#6b3f56;padding:.5rem .72rem;font-size:.72rem;font-weight:760;cursor:pointer;transition:border 160ms ease,background 160ms ease,color 160ms ease;}
    .admin-ai-quick-actions button:hover{border-color:rgba(128,0,0,.42);background:rgba(166,58,58,.1);color:var(--ai-red);}
    .admin-ai-history{min-height:0;min-width:0;overflow-y:auto;overflow-x:hidden;padding:.25rem 1rem 1rem;scrollbar-width:thin;scrollbar-color:rgba(128,0,0,.28) transparent;}
    .admin-ai-history::-webkit-scrollbar{width:5px;}
    .admin-ai-history::-webkit-scrollbar-thumb{border-radius:999px;background:rgba(128,0,0,.28);}
    .admin-ai-message{display:grid;grid-template-columns:2rem minmax(0,1fr);gap:.65rem;margin-bottom:.95rem;align-items:end;}
    .admin-ai-message-user{grid-template-columns:minmax(0,1fr) 2rem;}
    .admin-ai-avatar{display:flex;width:2rem;height:2rem;align-items:center;justify-content:center;border-radius:.85rem;background:rgba(166,58,58,.15);color:var(--ai-red);font-size:.68rem;font-weight:850;}
    .admin-ai-message-user .admin-ai-avatar{grid-column:2;grid-row:1;background:rgba(214,173,69,.18);color:#80600c;}
    .admin-ai-message-user>div:not(.admin-ai-avatar){grid-column:1;grid-row:1;text-align:right;}
    .admin-ai-bubble{display:inline-block;max-width:100%;border:1px solid rgba(33,19,21,.07);border-radius:1.15rem 1.15rem 1.15rem .35rem;background:#fff;color:#2b1c2d;padding:.72rem .85rem;box-shadow:0 8px 18px rgba(15,23,42,.06);font-size:.86rem;line-height:1.5;white-space:pre-line;overflow-wrap:anywhere;word-break:break-word;}
    .admin-ai-message-user .admin-ai-bubble{border-color:transparent;border-radius:1.15rem 1.15rem .35rem 1.15rem;background:linear-gradient(135deg,#efd4d4 0%,#eab4b4 100%);color:#2a1024;}
    .admin-ai-meta{display:block;margin-top:.3rem;color:rgba(123,96,112,.72);font-size:.62rem;font-weight:760;text-transform:uppercase;}
    .admin-ai-composer{border-top:1px solid rgba(214,173,69,.16);background:rgba(255,255,255,.78);padding:.85rem;backdrop-filter:blur(14px);}
    .admin-ai-file-preview{display:flex;align-items:center;justify-content:space-between;gap:.65rem;margin-bottom:.65rem;border:1px solid rgba(128,0,0,.15);border-radius:1rem;background:rgba(166,58,58,.08);padding:.45rem .45rem .45rem .68rem;}
    .admin-ai-file-meta{display:flex;align-items:center;gap:.5rem;min-width:0;}
    .admin-ai-file-meta span{display:flex;width:1.85rem;height:1.85rem;flex:0 0 auto;align-items:center;justify-content:center;border-radius:.65rem;background:#fff;color:var(--ai-red);}
    .admin-ai-file-meta span svg{width:1rem;height:1rem;}
    .admin-ai-file-meta p{min-width:0;margin:0;overflow:hidden;color:#5b4050;font-size:.76rem;font-weight:720;text-overflow:ellipsis;white-space:nowrap;}
    .admin-ai-input-row{display:flex;align-items:center;gap:.55rem;min-width:0;border:1px solid rgba(33,19,21,.08);border-radius:999px;background:#fff;padding:.38rem;box-shadow:0 10px 24px rgba(15,23,42,.06);}
    .admin-ai-attach-btn,.admin-ai-speak-btn,.admin-ai-voice-btn,.admin-ai-send-btn{display:flex;width:2.55rem;height:2.55rem;flex:0 0 auto;align-items:center;justify-content:center;border:0;border-radius:999px;cursor:pointer;}
    .admin-ai-attach-btn{background:rgba(166,58,58,.1);color:var(--ai-red);}
    .admin-ai-voice-btn{background:rgba(214,173,69,.14);color:#80600c;}
    .admin-ai-speak-btn{background:rgba(33,19,21,.06);color:#7b6070;}
    .admin-ai-speak-btn.is-on{background:rgba(34,197,94,.12);color:#15803d;}
    .admin-ai-voice-btn.is-listening{background:rgba(166,58,58,.16);color:var(--ai-red);box-shadow:0 0 0 4px rgba(166,58,58,.12);animation:admin-ai-listening 1.1s ease-in-out infinite;}
    .admin-ai-send-btn{background:linear-gradient(135deg,var(--ai-red-light),#f7e7b6);color:#241124;}
    #admin-ai-input{min-width:0;flex:1 1 auto;width:100%;border:0;background:transparent;color:#241124;font-size:.88rem;font-weight:620;outline:none;}
    #admin-ai-input::placeholder{color:rgba(123,96,112,.74);}
    .admin-ai-typing{display:flex;gap:.28rem;align-items:center;padding:.85rem .9rem;}
    .admin-ai-typing span{width:.45rem;height:.45rem;border-radius:999px;background:var(--ai-red-soft);animation:admin-ai-bounce 1.1s ease-in-out infinite;}
    .admin-ai-typing span:nth-child(2){animation-delay:.12s;}
    .admin-ai-typing span:nth-child(3){animation-delay:.24s;}
    @keyframes admin-ai-pulse{0%{transform:scale(.95);opacity:.75;}100%{transform:scale(1.28);opacity:0;}}
    @keyframes admin-ai-bounce{0%,80%,100%{transform:translateY(0);opacity:.45;}40%{transform:translateY(-.28rem);opacity:1;}}
    @keyframes admin-ai-listening{0%,100%{transform:scale(1);}50%{transform:scale(1.06);}}
    @media (max-width:820px){
        .admin-ai-panel{width:min(410px,calc(100vw - 1.5rem));max-width:calc(100vw - 1.5rem);}
        .admin-ai-quick-actions button{font-size:.69rem;padding:.46rem .65rem;}
        .admin-ai-input-row{gap:.42rem;}
        .admin-ai-attach-btn,.admin-ai-speak-btn,.admin-ai-voice-btn,.admin-ai-send-btn{width:2.35rem;height:2.35rem;}
    }
    @media (max-width:640px){
        #admin-ai-assistant{right:.75rem;bottom:.75rem;}
        .admin-ai-launcher{min-width:4rem;grid-template-columns:1fr;padding:.42rem;}
        .admin-ai-launcher-copy{display:none;}
        .admin-ai-panel{right:0;bottom:4.75rem;width:calc(100vw - 1.5rem);max-width:calc(100vw - 1.5rem);height:min(660px,calc(100vh - 5.5rem));border-radius:1.25rem;}
        .admin-ai-header,.admin-ai-hero,.admin-ai-composer{padding:.85rem;}
        .admin-ai-quick-actions{padding:0 .85rem .75rem;}
        .admin-ai-history{padding:.15rem .85rem .85rem;}
        .admin-ai-hero h3{font-size:.95rem;}
        .admin-ai-input-row{display:grid;grid-template-columns:auto minmax(0,1fr) auto auto auto;border-radius:1.2rem;padding:.42rem .45rem;}
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('admin-ai-toggle');
    const windowEl = document.getElementById('admin-ai-window');
    if (!toggle || !windowEl) return;

    const close = document.getElementById('admin-ai-close');
    const input = document.getElementById('admin-ai-input');
    const send = document.getElementById('admin-ai-send');
    const speakToggle = document.getElementById('admin-ai-speak-toggle');
    const voice = document.getElementById('admin-ai-voice');
    const history = document.getElementById('admin-ai-history');
    const fileInput = document.getElementById('admin-ai-file');
    const filePreview = document.getElementById('admin-ai-file-preview');
    const fileName = document.getElementById('admin-ai-file-name');
    const removeFile = document.getElementById('admin-ai-remove-file');
    const quickActions = document.querySelectorAll('[data-ai-prompt]');
    const token = @json(csrf_token());
    const chatUrl = @json(route('admin.ai.chat', [], false));
    const analyzeUrl = @json(route('admin.ai.analyze', [], false));

    let chatHistory = [];
    let selectedFile = null;
    let recognition = null;
    let isListening = false;
    let voiceRepliesEnabled = true;
    let voiceFinalTranscript = '';
    let voiceFallbackTimer = null;

    toggle.addEventListener('click', () => {
        windowEl.classList.toggle('hidden');
        if (!windowEl.classList.contains('hidden')) {
            input.focus();
            scrollHistory();
        }
    });

    close.addEventListener('click', () => {
        windowEl.classList.add('hidden');
        stopSpeaking();
    });

    if (!('speechSynthesis' in window) && speakToggle) {
        speakToggle.style.display = 'none';
        voiceRepliesEnabled = false;
    }

    if (speakToggle) {
        speakToggle.addEventListener('click', () => {
            voiceRepliesEnabled = !voiceRepliesEnabled;
            speakToggle.classList.toggle('is-on', voiceRepliesEnabled);
            speakToggle.title = voiceRepliesEnabled ? 'Voice replies on' : 'Voice replies off';
            speakToggle.setAttribute('aria-label', voiceRepliesEnabled ? 'Turn voice replies off' : 'Turn voice replies on');
            if (!voiceRepliesEnabled) stopSpeaking();
        });
    }

    quickActions.forEach((button) => {
        button.addEventListener('click', () => {
            input.value = button.dataset.aiPrompt || '';
            input.focus();
            sendMessage();
        });
    });

    fileInput.addEventListener('change', () => {
        selectedFile = fileInput.files[0] || null;
        if (!selectedFile) {
            clearFile();
            return;
        }

        fileName.textContent = selectedFile.name;
        filePreview.classList.remove('hidden');
        input.placeholder = 'Ask about this image...';
    });

    removeFile.addEventListener('click', clearFile);
    send.addEventListener('click', sendMessage);
    input.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            sendMessage();
        }
    });

    setupVoiceInput();

    async function sendMessage() {
        const text = input.value.trim();
        if (!text && !selectedFile) return;

        appendMessage('user', text || `Analyze image: ${selectedFile.name}`);
        input.value = '';
        const typing = appendTyping();

        try {
            let response;

            if (selectedFile) {
                const formData = new FormData();
                formData.append('file', selectedFile);
                formData.append('prompt', text || 'Analyze this image for the GAFALBUM admin.');
                formData.append('_token', token);

                response = await fetch(analyzeUrl, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: formData,
                });

                clearFile();
            } else {
                response = await fetch(chatUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({ message: text, history: chatHistory }),
                });
            }

            const data = await response.json();
            typing.remove();

            if (data.reply) {
                appendMessage('assistant', data.reply);
                speakReply(data.reply);
                if (text) chatHistory.push({ role: 'user', content: text });
                chatHistory.push({ role: 'assistant', content: data.reply });
                chatHistory = chatHistory.slice(-10);
                return;
            }

            const errorReply = data.error || 'I could not get a useful response right now.';
            appendMessage('assistant', errorReply);
            speakReply(errorReply);
        } catch (error) {
            typing.remove();
            const errorReply = 'Connection failed. Please check the AI configuration and try again.';
            appendMessage('assistant', errorReply);
            speakReply(errorReply);
        }
    }

    function clearFile() {
        selectedFile = null;
        fileInput.value = '';
        filePreview.classList.add('hidden');
        fileName.textContent = '';
        input.placeholder = 'Ask the admin assistant...';
    }

    function appendMessage(role, text) {
        const isUser = role === 'user';
        const row = document.createElement('div');
        row.className = `admin-ai-message ${isUser ? 'admin-ai-message-user' : 'admin-ai-message-bot'}`;

        const avatar = document.createElement('div');
        avatar.className = 'admin-ai-avatar';
        avatar.textContent = isUser ? 'ME' : 'AI';

        const content = document.createElement('div');
        const bubble = document.createElement('div');
        bubble.className = 'admin-ai-bubble';
        bubble.textContent = text;

        const label = document.createElement('span');
        label.className = 'admin-ai-meta';
        label.textContent = isUser ? 'Admin · now' : 'GAFALBUM AI · now';

        content.appendChild(bubble);
        content.appendChild(label);
        row.appendChild(avatar);
        row.appendChild(content);
        history.appendChild(row);
        scrollHistory();
    }

    function appendTyping() {
        const row = document.createElement('div');
        row.className = 'admin-ai-message admin-ai-message-bot';
        row.innerHTML = `
            <div class="admin-ai-avatar">AI</div>
            <div>
                <div class="admin-ai-bubble admin-ai-typing">
                    <span></span><span></span><span></span>
                </div>
                <span class="admin-ai-meta">Thinking</span>
            </div>
        `;
        history.appendChild(row);
        scrollHistory();
        return row;
    }

    function scrollHistory() {
        history.scrollTop = history.scrollHeight;
    }

    function setupVoiceInput() {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (!SpeechRecognition || !voice) {
            if (voice) voice.style.display = 'none';
            return;
        }

        recognition = new SpeechRecognition();
        recognition.continuous = false;
        recognition.interimResults = true;
        recognition.lang = 'en-US';
        recognition.maxAlternatives = 3;

        voice.addEventListener('click', () => {
            if (isListening) {
                recognition.stop();
                return;
            }

            voiceFinalTranscript = '';
            window.clearTimeout(voiceFallbackTimer);
            input.placeholder = 'Listening...';
            input.value = '';
            try { recognition.start(); } catch (error) {
                input.placeholder = selectedFile ? 'Ask about this image...' : 'Ask the admin assistant...';
            }
        });

        recognition.onstart = () => {
            isListening = true;
            stopSpeaking();
            voice.classList.add('is-listening');
            voice.title = 'Listening... click to stop';
            voice.setAttribute('aria-label', 'Stop voice input');
            input.placeholder = 'Listening clearly... speak now';
        };

        recognition.onresult = (event) => {
            let interimTranscript = '';
            let finalTranscript = '';

            for (let index = event.resultIndex; index < event.results.length; index++) {
                const result = event.results[index];
                const transcript = Array.from(result).sort((a, b) => (b.confidence || 0) - (a.confidence || 0))[0]?.transcript || '';
                if (result.isFinal) finalTranscript += transcript;
                else interimTranscript += transcript;
            }

            if (finalTranscript.trim()) voiceFinalTranscript = `${voiceFinalTranscript} ${finalTranscript}`.trim();
            input.value = (voiceFinalTranscript || interimTranscript).trim();
            input.placeholder = interimTranscript ? 'Still listening...' : 'Finishing up...';

            window.clearTimeout(voiceFallbackTimer);
            voiceFallbackTimer = window.setTimeout(() => {
                if (isListening && input.value.trim()) recognition.stop();
            }, 1200);
        };

        recognition.onspeechend = () => {
            window.clearTimeout(voiceFallbackTimer);
            voiceFallbackTimer = window.setTimeout(() => {
                if (isListening) recognition.stop();
            }, 650);
        };

        recognition.onerror = (event) => {
            const messages = {
                'not-allowed': 'Microphone blocked. Allow mic access in your browser.',
                'audio-capture': 'No microphone found. Check your input device.',
                'no-speech': 'I did not hear anything. Try again closer to the mic.',
                'network': 'Voice recognition network issue. Try again.',
            };
            input.placeholder = messages[event.error] || 'Voice input failed. Try again.';
            voiceFinalTranscript = '';
            window.clearTimeout(voiceFallbackTimer);
        };

        recognition.onend = () => {
            const heardText = input.value.trim();
            isListening = false;
            voice.classList.remove('is-listening');
            voice.title = 'Ask with voice';
            voice.setAttribute('aria-label', 'Ask with voice');
            window.clearTimeout(voiceFallbackTimer);

            if (heardText) {
                input.value = heardText;
                input.placeholder = 'Sending voice message...';
                window.setTimeout(() => {
                    if (input.value.trim() === heardText) sendMessage();
                }, 220);
                return;
            }

            input.placeholder = selectedFile ? 'Ask about this image...' : 'Ask the admin assistant...';
        };
    }

    function speakReply(text) {
        if (!voiceRepliesEnabled || !('speechSynthesis' in window)) return;
        const cleanText = String(text || '').replace(/https?:\/\/\S+/g, '').replace(/\s+/g, ' ').trim();
        if (!cleanText) return;
        stopSpeaking();

        const utterance = new SpeechSynthesisUtterance(cleanText);
        utterance.rate = 0.98;
        utterance.pitch = 1.02;
        utterance.volume = 1;

        const voices = window.speechSynthesis.getVoices();
        const preferredVoice = voices.find((item) => /female|zira|susan|samantha|google uk english female/i.test(item.name))
            || voices.find((item) => /english/i.test(item.lang))
            || voices[0];

        if (preferredVoice) utterance.voice = preferredVoice;
        window.speechSynthesis.speak(utterance);
    }

    function stopSpeaking() {
        if ('speechSynthesis' in window) window.speechSynthesis.cancel();
    }
});
</script>
