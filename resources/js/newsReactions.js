export class NewsReactionHandler {
    constructor() {
        this.initializeReactions();
        this.initializeSubscription();
    }

    initializeReactions() {
        const reactionButtons = document.querySelectorAll('[data-reaction-button]');
        
        reactionButtons.forEach(button => {
            button.addEventListener('click', async (e) => {
                e.preventDefault();
                
                if (button.classList.contains('umodal_toggle')) {
                    window.openLoginModal();
                    return;
                }
                
                const newsId = button.dataset.newsId;
                const reactionType = button.dataset.reactionButton;
                const isActive = button.classList.contains(`${reactionType}d`);
                
                try {
                    const response = await fetch(`/reactions/${newsId}/${reactionType}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    const data = await response.json();
                    
                    // Update button state
                    button.classList.toggle(`${reactionType}d`);
                    
                    // Update count
                    const countSpan = button.querySelector(`[data-reaction-count="${reactionType}"]`);
                    if (countSpan) {
                        countSpan.textContent = data.count;
                    }
                    
                } catch (error) {
                    console.error('Error:', error);
                }
            });
        });
    }

    initializeSubscription() {
        const subscribeButtons = document.querySelectorAll('[data-subscription-button]');
        
        subscribeButtons.forEach(button => {
            button.addEventListener('click', async (e) => {
                e.preventDefault();
                
                if (button.classList.contains('umodal_toggle')) {
                    window.openLoginModal();
                    return;
                }
                
                const channelId = button.dataset.channelId;
                
                try {
                    const response = await fetch(`/subscribers/${channelId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    const data = await response.json();
                    
                    // Update button state
                    button.classList.toggle('subscribed');
                    
                    // Update button text
                    const textSpan = button.querySelector('span');
                    textSpan.textContent = data.message;
                    
                    // Update button icon
                    const svg = button.querySelector('svg');
                    svg.innerHTML = data.isSubscribed 
                        ? `<path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />`
                        : `<path stroke-linecap="round" stroke-linejoin="round" d="M9.143 17.082a24.248 24.248 0 0 0 3.844.148m-3.844-.148a23.856 23.856 0 0 1-5.455-1.31 8.964 8.964 0 0 0 2.3-5.542m3.155 6.852a3 3 0 0 0 5.667 1.97m1.965-2.277L21 21m-4.225-4.225a23.81 23.81 0 0 0 3.536-1.003A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6.53 6.53m10.245 10.245L6.53 6.53M3 3l3.53 3.53" />`;
                    
                    // Update subscriber count
                    const subscriberCount = document.getElementById('subscriberCount');
                    if (subscriberCount) {
                        subscriberCount.textContent = new Intl.NumberFormat().format(data.subscriberCount);
                    }
                    
                } catch (error) {
                    console.error('Error:', error);
                }
            });
        });
    }
}