<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KP Planshop AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass { background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.1); }
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-950 text-white flex items-center justify-center min-h-screen p-4">

    <div class="glass w-full max-w-2xl h-[85vh] rounded-[2.5rem] flex flex-col shadow-2xl overflow-hidden">
        <div class="p-6 border-b border-white/5 bg-white/5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center font-bold shadow-blue-500/20 shadow-lg">KP</div>
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-widest">Architect AI</h2>
                    <p class="text-[10px] text-blue-400">KP Planshop Official Assistant</p>
                </div>
            </div>
        </div>

        <div id="chat-box" class="flex-1 overflow-y-auto p-6 space-y-4 custom-scroll">
            <div class="bg-white/5 p-4 rounded-2xl rounded-tl-none border border-white/5 text-sm max-w-[85%]">
                Hello! I am your KP Planshop assistant. How can I help with your house plans today?
            </div>
        </div>

        <div class="p-6 bg-white/5 border-t border-white/5">
            <div class="relative flex items-center bg-black/40 rounded-2xl border border-white/10 p-2 focus-within:border-blue-500 transition">
                <input type="text" id="user-input" placeholder="Ask about 3-bedroom plans..." 
                    class="flex-1 bg-transparent border-none outline-none px-3 text-sm">
                <button onclick="askAI()" class="bg-blue-600 hover:bg-blue-500 p-2.5 rounded-xl transition shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        async function askAI() {
    const box = document.getElementById('chat-box');
    const input = document.getElementById('user-input');
    const val = input.value.trim();
    if(!val) return;

    // 1. Add User Message
    box.innerHTML += `<div class="flex justify-end mb-4"><div class="bg-blue-600 p-3 rounded-xl text-sm">${val}</div></div>`;
    
    // 2. Add "Thinking" status
    const tempId = 'status-' + Date.now();
    box.innerHTML += `<div id="${tempId}" class="text-[10px] text-gray-500 italic mb-4">Contacting KP Server...</div>`;
    input.value = "";
    box.scrollTop = box.scrollHeight;

    try {
        const res = await fetch('chat.php', {
            method: 'POST',
            body: JSON.stringify({ message: val })
        });

        const rawText = await res.text();
        document.getElementById(tempId).remove();

        // 3. CHECK FOR SERVER CRASH (HTML output)
        if (rawText.trim().startsWith("<")) {
            showClearError("PHP_CRASH", "Your PHP file has a code error. It is sending HTML instead of JSON.", rawText);
            return;
        }

        const data = JSON.parse(rawText);

        // 4. CHECK FOR API/CONFIG ERRORS
        if (data.error) {
            showClearError(data.error, data.detail || "General Error", JSON.stringify(data, null, 2));
        } else {
            // SUCCESS
            box.innerHTML += `<div class="flex justify-start mb-4"><div class="bg-white/10 p-3 rounded-xl text-sm border border-white/5">${data.reply}</div></div>`;
        }

    } catch (err) {
        if(document.getElementById(tempId)) document.getElementById(tempId).remove();
        showClearError("NETWORK_FAIL", "Cannot reach chat.php. Check your internet or file path.", err.message);
    }
    box.scrollTop = box.scrollHeight;
}

function showClearError(title, message, technical) {
    const box = document.getElementById('chat-box');
    const errorHtml = `
        <div class="my-4 p-4 rounded-xl bg-red-950/50 border-2 border-red-500 text-white shadow-2xl">
            <div class="flex items-center gap-2 mb-2">
                <span class="bg-red-500 text-[10px] font-bold px-2 py-0.5 rounded uppercase">Error: ${title}</span>
            </div>
            <p class="text-sm font-semibold mb-3 text-red-200">${message}</p>
            
            <div class="bg-black/60 p-3 rounded border border-red-500/30">
                <p class="text-[9px] uppercase tracking-widest text-gray-400 mb-1">Technical Detail / Raw Output:</p>
                <pre class="text-[10px] font-mono text-red-300 overflow-x-auto whitespace-pre-wrap">${technical}</pre>
            </div>
            
            <p class="mt-3 text-[10px] text-gray-400 italic">Solution: Check your .env file and PHP syntax.</p>
        </div>`;
    box.innerHTML += errorHtml;
}
    </script>
</body>
</html>