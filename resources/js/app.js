import './bootstrap';
import Echo from 'laravel-echo';
import io from 'socket.io-client';


window.io = io;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: false,
    disableStats: true,
    encrypted: false
});

// Listen for events globally
document.addEventListener("DOMContentLoaded", () => {
    const rxWindow = document.getElementById('rxWindow');
    if (!rxWindow) return;

    window.Echo.channel('machine.TEST_DEVICE')
        .listen('.device.data', (e) => {
            const div = document.createElement('div');
            div.textContent = `[${new Date().toLocaleTimeString()}] ${JSON.stringify(e.data)}`;
            rxWindow.appendChild(div);
            rxWindow.scrollTop = rxWindow.scrollHeight;
        });
});
