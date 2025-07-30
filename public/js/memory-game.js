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
    console.log("🔄 Initializing Memory Game...");

    // Tunggu sebentar untuk memastikan modal sudah ter-render
    setTimeout(() => {
        memoryCards = document.querySelectorAll(".memory-card");

        console.log(`🎯 Found ${memoryCards.length} memory cards`);

        if (memoryCards.length === 0) {
            console.error("❌ No memory cards found!");
            return;
        }

        // Reset all cards
        memoryCards.forEach((card, index) => {
            console.log(`🎴 Resetting memory card ${index + 1}:`, card);

            // Reset classes - hanya gunakan memory-card
            card.className = "memory-card";

            // Clear all content
            card.innerHTML = "";
            card.textContent = "";
            card.innerText = "";

            // Hapus inline styles dan biarkan CSS mengatur
            card.removeAttribute("style");

            // Remove any existing event listeners
            card.removeEventListener("click", flipMemoryCard);

            // Add click listener
            card.addEventListener("click", flipMemoryCard);

            // Debug: Add event listener untuk memastikan klik terdeteksi
            card.addEventListener("click", function (e) {
                console.log(
                    "🖱️ Click detected on card:",
                    this,
                    "with emoji:",
                    this.dataset.emoji
                );
            });

            console.log(`✅ Card ${index + 1} reset and ready`);
        });

        // Shuffle cards
        shuffleMemoryCards();

        console.log("🎮 Memory Game initialization complete!");

        // Tunggu animasi CSS selesai, lalu pastikan kartu bisa diklik
        setTimeout(() => {
            memoryCards.forEach((card) => {
                card.style.pointerEvents = "auto";
                card.style.cursor = "pointer";
                console.log("✅ Card enabled for clicking");
            });
        }, 2000); // Tunggu 2 detik untuk animasi selesai
    }, 100); // Tunggu 100ms untuk DOM ready
}

function shuffleMemoryCards() {
    console.log("🔀 Shuffling memory cards...");
    const emojis = ["🫶", "💞", "🥳", "❤️‍🔥", "💐", "😍", "🥰", "🩷"];
    const doubledEmojis = [...emojis, ...emojis]; // Create pairs

    // Fisher-Yates shuffle
    for (let i = doubledEmojis.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [doubledEmojis[i], doubledEmojis[j]] = [
            doubledEmojis[j],
            doubledEmojis[i],
        ];
    }

    // Assign shuffled emojis to cards
    memoryCards.forEach((card, index) => {
        card.dataset.emoji = doubledEmojis[index];
        console.log(
            `🎯 Card ${index + 1} assigned emoji: ${doubledEmojis[index]}`
        );
    });
}

function flipMemoryCard() {
    console.log("🎯 Card clicked!");
    console.log("🔍 Card dataset emoji:", this.dataset.emoji);
    console.log("🔍 Card element:", this);

    if (lockBoard) {
        console.log("❌ Board is locked");
        return;
    }
    if (this === firstCard) {
        console.log("❌ Same card clicked twice");
        return;
    }
    if (this.classList.contains("matched")) {
        console.log("❌ Card already matched");
        return;
    }
    if (this.classList.contains("flipped")) {
        console.log("❌ Card already flipped");
        return;
    }

    console.log("🎴 Flipping memory card:", this.dataset.emoji);

    const emoji = this.dataset.emoji;

    if (!emoji) {
        console.error("❌ No emoji found in dataset!");
        return;
    }

    // Add flipped class for CSS styling
    this.classList.add("flipped");

    // Set emoji content dengan multiple methods + force styling
    this.innerHTML = emoji;
    this.textContent = emoji;

    // Force inline styling untuk memastikan emoji tampil
    this.style.fontSize = "2em";
    this.style.color = "#000";
    this.style.backgroundColor = "white";
    this.style.border = "3px solid #4CAF50";
    this.style.display = "flex";
    this.style.alignItems = "center";
    this.style.justifyContent = "center";
    this.style.opacity = "1";
    this.style.transform = "scale(1)";

    console.log("✅ Memory card revealed with emoji:", emoji);
    console.log("✅ Card classes:", this.className);
    console.log("✅ Card innerHTML:", this.innerHTML);
    console.log("✅ Card textContent:", this.textContent);

    if (!hasFlippedCard) {
        hasFlippedCard = true;
        firstCard = this;
        console.log("🥇 First card selected");
        return;
    }

    secondCard = this;
    console.log("🥈 Second card selected");
    checkForMemoryMatch();
}

function checkForMemoryMatch() {
    console.log("🔍 Checking for memory match...");
    let isMatch = firstCard.dataset.emoji === secondCard.dataset.emoji;

    console.log(`First card: ${firstCard.dataset.emoji}`);
    console.log(`Second card: ${secondCard.dataset.emoji}`);
    console.log(`Match: ${isMatch}`);

    isMatch ? disableMemoryCards() : unflipMemoryCards();
}

function disableMemoryCards() {
    console.log("✅ Cards matched! Disabling...");

    firstCard.removeEventListener("click", flipMemoryCard);
    secondCard.removeEventListener("click", flipMemoryCard);

    // Style matched cards using CSS class
    [firstCard, secondCard].forEach((card) => {
        card.classList.remove("flipped");
        card.classList.add("matched");
        const emoji = card.dataset.emoji;

        // Ensure emoji stays visible with multiple methods + force styling
        card.innerHTML = emoji;
        card.textContent = emoji;
        card.innerText = emoji;

        // Force inline styling untuk matched cards
        card.style.fontSize = "2em";
        card.style.color = "#000";
        card.style.backgroundColor = "rgba(144, 238, 144, 0.9)";
        card.style.border = "3px solid #4CAF50";
        card.style.display = "flex";
        card.style.alignItems = "center";
        card.style.justifyContent = "center";
        card.style.opacity = "1";
        card.style.transform = "scale(1)";
        card.style.cursor = "default";

        console.log("✅ Matched card styled with emoji:", emoji);
    });

    matchedPairs++;
    console.log(`💯 Matched pairs: ${matchedPairs}/8`);

    if (matchedPairs === 8) {
        console.log("🎉 Memory Game Complete!");
        endMemoryGame();
    }

    resetMemoryBoard();
}

function unflipMemoryCards() {
    console.log("❌ Cards do not match. Unflipping...");
    lockBoard = true;

    setTimeout(() => {
        // Reset cards to hidden state
        [firstCard, secondCard].forEach((card) => {
            console.log("🔄 Resetting card:", card.dataset.emoji);

            // Remove flipped class, kembali ke memory-card saja
            card.className = "memory-card";

            // Clear content
            card.innerHTML = "";
            card.textContent = "";

            console.log("✅ Card reset to hidden state");
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
