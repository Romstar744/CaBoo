let lastMessageId = 0;

function getMessages() {
    const chatId = new URLSearchParams(window.location.search).get('chat_id');
    if (!chatId) {
        console.error("Chat ID не найден в URL.");
        return;
    }

    fetch('get_messages.php?chat_id=' + chatId + '&last_message_id=' + lastMessageId)
        .then(response => response.json())
        .then(data => {
            const messagesDiv = document.querySelector('.messages');

            data.forEach(message => {
                if (message.id > lastMessageId) {
                    const messageDiv = document.createElement('div');
                    messageDiv.classList.add('message');
                    if (message.sender_id == userId) {
                        messageDiv.classList.add('sent');
                    } else {
                        messageDiv.classList.add('received');
                    }
                    const messageTime = new Date(message.created_at).toLocaleString();
                    messageDiv.innerHTML = `
                        <span class="sender">${message.username}:</span>
                        <span class="text">${message.message}</span>
                        <span class="time">${messageTime}</span>
                    `;
                    messagesDiv.appendChild(messageDiv);
                    lastMessageId = message.id;
                }
            });
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        })
        .catch(error => {
            console.error('Ошибка при получении сообщений:', error);
        });
}

setInterval(getMessages, 2000);

window.onload = function() {
    const messagesDiv = document.querySelector('.messages');
    if (messagesDiv) {
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }
    getMessages();
};
