const avatar = document.querySelector('.profile-avatar img');

avatar.addEventListener('mouseover', () => {
    avatar.style.transform = 'rotate(10deg)';
    avatar.style.transition = 'transform 0.3s ease-in-out';
});

avatar.addEventListener('mouseout', () => {
    avatar.style.transform = 'rotate(0deg)';
});