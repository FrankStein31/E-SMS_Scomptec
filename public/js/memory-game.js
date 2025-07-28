// Memory Game Logic - Brute Force Emoji Display
let memoryCurrentStage = 1;
let memoryGameComplete = false;
let memoryCards = [];
let hasFlippedCard = false;
let lockBoard = false;
let firstCard, secondCard;
let matchedPairs = 0;

function openMemoryGame() {
    console.log('🎮 Opening Memory Game...');
    
    // Reset game state
    memoryCurrentStage = 1;
    memoryGameComplete = false;
    hasFlippedCard = false;
    lockBoard = false;
    firstCard = null;
    secondCard = null;
    matchedPairs = 0;

    // Show stage 1 and hide others
    document.getElementById('memoryStage1').classList.remove('d-none');
    document.getElementById('memoryStage2').classList.add('d-none');
    document.getElementById('memoryStage3').classList.add('d-none');
    document.getElementById('memoryStage4').classList.add('d-none');

    // Open modal
    const modal = new bootstrap.Modal(document.getElementById('memoryGameModal'));
    modal.show();

    // Auto-play Forever Young music
    const audio = document.getElementById('foreverYoungAudio');
    if (audio) {
        audio.play().catch(e => console.log('Audio play failed:', e));
    }
}

function nextMemoryStage(stageNumber) {
    console.log(`🎯 Moving to Memory Stage ${stageNumber}`);
    document.getElementById(`memoryStage${memoryCurrentStage}`).classList.add('d-none');
    document.getElementById(`memoryStage${stageNumber}`).classList.remove('d-none');
    memoryCurrentStage = stageNumber;

    if (stageNumber === 3) {
        setTimeout(initMemoryGame, 500);
    }
}

function initMemoryGame() {
    console.log('🔄 Initializing Memory Game...');
    memoryCards = document.querySelectorAll('.memory-card');
    
    console.log(`🎯 Found ${memoryCards.length} memory cards`);
    
    // Reset all cards with brute force approach
    memoryCards.forEach((card, index) => {
        console.log(`🎴 Resetting memory card ${index + 1}:`, card);
        
        // Remove all classes and styling
        card.classList.remove('flipped', 'matched', 'memory-revealed-brute-force');
        card.textContent = '';
        card.innerHTML = '';
        
        // Force reset styling
        card.setAttribute('style', `
            width: 60px !important;
            height: 60px !important;
            background: rgba(255, 255, 255, 0.8) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 12px !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            opacity: 1 !important;
            transform: scale(1) !important;
            font-size: 0 !important;
            color: transparent !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            position: relative !important;
        `);
        
        // Remove any existing event listeners
        card.removeEventListener('click', flipMemoryCard);
        
        // Add click listener
        card.addEventListener('click', flipMemoryCard);
        
        console.log(`✅ Card ${index + 1} reset and ready, emoji will be:`, card.dataset.emoji);
    });

    // Shuffle cards
    shuffleMemoryCards();
    
    console.log('🎮 Memory Game initialization complete!');
}

function shuffleMemoryCards() {
    console.log('🔀 Shuffling memory cards...');
    const emojis = ['🫶', '💞', '🥳', '❤️‍🔥', '💐', '😍', '🥰', '🩷'];
    const doubledEmojis = [...emojis, ...emojis]; // Create pairs
    
    // Fisher-Yates shuffle
    for (let i = doubledEmojis.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [doubledEmojis[i], doubledEmojis[j]] = [doubledEmojis[j], doubledEmojis[i]];
    }

    // Assign shuffled emojis to cards
    memoryCards.forEach((card, index) => {
        card.dataset.emoji = doubledEmojis[index];
        console.log(`🎯 Card ${index + 1} assigned emoji: ${doubledEmojis[index]}`);
    });
}

function flipMemoryCard() {
    if (lockBoard) return;
    if (this === firstCard) return;
    if (this.classList.contains('matched')) return;
    if (this.classList.contains('memory-revealed-brute-force')) return;

    console.log('🎴 Flipping memory card:', this.dataset.emoji);

    // ULTRA BRUTE FORCE - COPY EXACT METHOD FROM SCRATCH CARD
    const emoji = this.dataset.emoji;
    
    // Clear everything first
    this.innerHTML = '';
    this.textContent = '';
    this.className = 'memory-card';
    
    // Apply the EXACT same styling as successful scratch card
    this.classList.add('revealed-brute-force');
    this.setAttribute('style', `
        width: 60px !important;
        height: 60px !important;
        background: white !important;
        border: 3px solid #4CAF50 !important;
        border-radius: 12px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 2em !important;
        color: #000 !important;
        font-family: "Segoe UI Emoji", "Apple Color Emoji", "Noto Color Emoji", Arial, sans-serif !important;
        text-align: center !important;
        line-height: 1 !important;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3) !important;
        z-index: 999 !important;
        animation: revealPulse 0.5s ease !important;
        position: relative !important;
        cursor: pointer !important;
    `);
    
    // Set emoji content
    this.textContent = emoji;
    this.innerHTML = emoji;
    
    // Force re-render
    this.offsetHeight;
    this.style.display = 'none';
    this.offsetHeight;
    this.style.display = 'flex';
    
    console.log('✅ Memory card revealed with emoji:', emoji);
    console.log('✅ Card element after styling:', this);

    if (!hasFlippedCard) {
        hasFlippedCard = true;
        firstCard = this;
        return;
    }

    secondCard = this;
    checkForMemoryMatch();
}

function checkForMemoryMatch() {
    console.log('🔍 Checking for memory match...');
    let isMatch = firstCard.dataset.emoji === secondCard.dataset.emoji;
    
    console.log(`First card: ${firstCard.dataset.emoji}`);
    console.log(`Second card: ${secondCard.dataset.emoji}`);
    console.log(`Match: ${isMatch}`);
    
    isMatch ? disableMemoryCards() : unflipMemoryCards();
}

function disableMemoryCards() {
    console.log('✅ Cards matched! Disabling...');
    
    firstCard.removeEventListener('click', flipMemoryCard);
    secondCard.removeEventListener('click', flipMemoryCard);
    
    // Style matched cards with SAME technique as revealed
    [firstCard, secondCard].forEach(card => {
        card.classList.add('matched');
        const emoji = card.dataset.emoji;
        
        card.setAttribute('style', `
            width: 60px !important;
            height: 60px !important;
            background: rgba(144, 238, 144, 0.9) !important;
            border: 3px solid #4CAF50 !important;
            border-radius: 12px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 2em !important;
            color: #000 !important;
            font-family: "Segoe UI Emoji", "Apple Color Emoji", "Noto Color Emoji", Arial, sans-serif !important;
            text-align: center !important;
            line-height: 1 !important;
            box-shadow: 0 4px 8px rgba(76, 175, 80, 0.5) !important;
            z-index: 999 !important;
            cursor: default !important;
            transform: scale(1) !important;
            transition: all 0.3s ease !important;
        `);
        
        // Ensure emoji stays visible
        card.textContent = emoji;
        card.innerHTML = emoji;
    });

    matchedPairs++;
    console.log(`💯 Matched pairs: ${matchedPairs}/8`);
    
    if (matchedPairs === 8) {
        console.log('🎉 Memory Game Complete!');
        endMemoryGame();
    }

    resetMemoryBoard();
}

function unflipMemoryCards() {
    console.log('❌ Cards do not match. Unflipping...');
    lockBoard = true;

    setTimeout(() => {
        // Reset cards to hidden state using SAME method as scratch cards
        [firstCard, secondCard].forEach(card => {
            console.log('🔄 Resetting card:', card.dataset.emoji);
            
            card.classList.remove('revealed-brute-force');
            card.className = 'memory-card';
            card.textContent = '';
            card.innerHTML = '';
            
            // Reset to original hidden state
            card.setAttribute('style', `
                width: 60px !important;
                height: 60px !important;
                background: rgba(255, 255, 255, 0.8) !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                border-radius: 12px !important;
                cursor: pointer !important;
                transition: all 0.3s ease !important;
                opacity: 1 !important;
                transform: scale(1) !important;
                font-size: 0 !important;
                color: transparent !important;
                border: 1px solid rgba(255, 255, 255, 0.3) !important;
            `);
            
            // Force re-render
            card.offsetHeight;
            
            console.log('✅ Card reset to hidden state');
        });

        resetMemoryBoard();
    }, 1000);
}

function resetMemoryBoard() {
    [hasFlippedCard, lockBoard] = [false, false];
    [firstCard, secondCard] = [null, null];
}

function endMemoryGame() {
    memoryGameComplete = true;
    
    setTimeout(() => {
        // Hide game board and show success stage
        document.getElementById('memoryStage3').classList.add('d-none');
        document.getElementById('memoryStage4').classList.remove('d-none');

        // Start typing animation after a delay
        setTimeout(startMemoryTypingAnimation, 1000);

        // Start heart rain
        setTimeout(() => {
            document.getElementById('memoryLoveAnimation').classList.remove('d-none');
            startMemoryHeartRain();
        }, 2000);

    }, 1000);
}

function startMemoryTypingAnimation() {
    const texts = [
        "Di dunia, yang luas ini\nada 87% manusia~",
        "Dan 70% air di dalamnya~", 
        "Tapi kalau hatiku?? 🤔\n\n1000% isinya cuma kamuu 😆🫵"
    ];

    let currentTextIndex = 0;
    const typingElement = document.getElementById('typingText');
    
    function typeText() {
        if (currentTextIndex >= texts.length) {
            // Show final message
            setTimeout(showFinalMemoryMessage, 1000);
            return;
        }

        typingElement.innerHTML = '';
        typingElement.classList.add('typing-effect');
        
        const text = texts[currentTextIndex];
        let charIndex = 0;

        function typeChar() {
            if (charIndex < text.length) {
                if (text[charIndex] === '\n') {
                    typingElement.innerHTML += '<br>';
                } else {
                    typingElement.innerHTML += text[charIndex];
                }
                charIndex++;
                setTimeout(typeChar, 50);
            } else {
                typingElement.classList.remove('typing-effect');
                setTimeout(() => {
                    currentTextIndex++;
                    setTimeout(typeText, 1000);
                }, 1500);
            }
        }

        typeChar();
    }

    document.getElementById('finalMessage').classList.remove('d-none');
    typeText();
}

function showFinalMemoryMessage() {
    const finalMessage = document.createElement('div');
    finalMessage.className = 'mt-4 text-white';
    finalMessage.innerHTML = `
        <h3 style="color: #ff6b9d;">ᯓᡣ𐭩</h3>
        <p><strong>Lopyuu ayangkuu</strong> tersayang,<br>
        termanis, terlucu, terimuutttt<br>
        <strong>semangat terus yaw 🫣😍😋💐</strong></p>
    `;
    
    document.getElementById('memoryResultText').appendChild(finalMessage);
    
    // Change sticker to love
    document.getElementById('memoryResultSticker').src = 'https://htmlku.com/0/panda/terlope2.gif';
}

function startMemoryHeartRain() {
    const heartRain = document.querySelector('#memoryLoveAnimation .heart-rain');
    
    function createMemoryHeart() {
        const heart = document.createElement('div');
        heart.className = 'heart-fall';
        heart.innerHTML = ['❤️', '💕', '💖', '💗', '💘', '💝', '🩷', '🫶'][Math.floor(Math.random() * 8)];
        heart.style.left = Math.random() * 100 + 'vw';
        heart.style.animationDuration = (Math.random() * 3 + 2) + 's';
        heart.style.fontSize = (Math.random() * 10 + 15) + 'px';

        heartRain.appendChild(heart);

        // Remove heart after animation
        setTimeout(() => {
            if (heart.parentNode) {
                heart.parentNode.removeChild(heart);
            }
        }, 5000);
    }

    // Create hearts continuously
    const heartInterval = setInterval(createMemoryHeart, 300);

    // Stop after 10 seconds
    setTimeout(() => {
        clearInterval(heartInterval);
    }, 10000);
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎮 Memory Game JS loaded!');
    
    const memoryModal = document.getElementById('memoryGameModal');
    if (memoryModal) {
        memoryModal.addEventListener('hidden.bs.modal', function () {
            const audio = document.getElementById('foreverYoungAudio');
            if (audio) {
                audio.pause();
                audio.currentTime = 0;
            }
        });

        // Add click effect for memory game
        memoryModal.addEventListener('click', function(e) {
            const circle = document.createElement("div");
            circle.classList.add("memory-click-effect");
            circle.style.left = `${e.pageX}px`;
            circle.style.top = `${e.pageY}px`;

            document.body.appendChild(circle);

            circle.addEventListener("animationend", () => {
                circle.remove();
            });
        });
    }
});
