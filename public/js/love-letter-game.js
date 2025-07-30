// Love Letter Game Logic - Based on catatan.html
let loveAudio;
let loveCurrentStage = 'cover';
let isLoveTyping = false;

function openLoveLetterGame() {
    console.log('💌 Opening Love Letter Game...');
    
    // Initialize audio
    if (!loveAudio) {
        loveAudio = new Audio('https://feeldreams.github.io/audio/thousand.mp3');
        loveAudio.loop = true;
    }
    
    // Reset game state
    loveCurrentStage = 'cover';
    isLoveTyping = false;
    
    // Show cover screen
    document.getElementById('loveCoverScreen').style.display = 'flex';
    document.getElementById('loveEnvelopeWrapper').style.transform = 'scale(0)';
    document.getElementById('loveOpenButton').style.transform = 'scale(0)';
    document.getElementById('loveLetterContent').classList.add('d-none');
    
    // Reset envelope state
    const envelope = document.getElementById('loveEnvelope');
    envelope.classList.remove('open');
    envelope.classList.add('close');
    
    // Open modal
    const modal = new bootstrap.Modal(document.getElementById('loveLetterGameModal'));
    modal.show();
}

function startLoveLetterGame() {
    console.log('💌 Starting Love Letter Game...');
    
    // Play audio
    loveAudio.play().catch(e => console.log('Audio play failed:', e));
    
    // Hide cover screen
    const coverScreen = document.getElementById('loveCoverScreen');
    coverScreen.style.opacity = '0';
    coverScreen.style.transition = 'all .6s ease';
    
    setTimeout(() => {
        coverScreen.style.display = 'none';
        
        // Show envelope and button
        document.getElementById('loveEnvelopeWrapper').style.transform = 'scale(1)';
        document.getElementById('loveEnvelopeWrapper').style.transition = 'all .6s ease';
        
        document.getElementById('loveOpenButton').style.transform = 'scale(1)';
        document.getElementById('loveOpenButton').style.transition = 'all .6s ease';
        
        loveCurrentStage = 'envelope';
    }, 300);
}

function openLoveLetter() {
    if (loveCurrentStage !== 'envelope') return;
    
    console.log('💌 Opening love letter...');
    
    const envelope = document.getElementById('loveEnvelope');
    const envelopeWrapper = document.getElementById('loveEnvelopeWrapper');
    const openButton = document.getElementById('loveOpenButton');
    
    // Open envelope animation
    envelope.classList.remove('close');
    envelope.classList.add('open');
    
    // Hide button
    openButton.style.transform = 'scale(0)';
    openButton.style.transition = 'all .6s ease';
    
    loveCurrentStage = 'opening';
    
    setTimeout(() => {
        // Hide envelope and show letter content
        envelopeWrapper.style.opacity = '0';
        envelopeWrapper.style.transform = 'scale(0)';
        
        setTimeout(() => {
            envelopeWrapper.style.display = 'none';
            showLoveLetterContent();
        }, 700);
    }, 1400);
}

function showLoveLetterContent() {
    console.log('💌 Showing letter content...');
    
    const letterContent = document.getElementById('loveLetterContent');
    const sticker = document.querySelector('.love-sticker');
    const container = document.querySelector('.love-container');
    
    // Show letter content
    letterContent.classList.remove('d-none');
    
    // Animate sticker
    setTimeout(() => {
        sticker.classList.add('show');
    }, 100);
    
    // Animate container
    setTimeout(() => {
        container.classList.add('show');
        startLoveTypingAnimation();
    }, 600);
    
    loveCurrentStage = 'reading';
}

function startLoveTypingAnimation() {
    if (isLoveTyping) return;
    
    console.log('💌 Starting typing animation...');
    isLoveTyping = true;
    
    const titleElement = document.querySelector('.love-title-text');
    const messageElement = document.querySelector('.love-message-text');
    
    // Clear existing content
    titleElement.innerHTML = '';
    messageElement.innerHTML = '';
    
    const title = "Alooo Kamuu! 🫣💗";
    const messages = [
        "Tetap semangat yaa! Aku tau kamu kuat, meskipun masalah yang datang ngga selalu ringan. Aku selalu ada di sini buat dengerin kamu, nemenin kamu, meskipun cuma dari jauh. Aku ngerti kalau kadang suasana di rumah bikin kamu capek, tapi jangan nyerah yaa! Aku percaya kamu bisa lewatin semuanya. Aku mungkin belum bisa bantu banyak, tapi aku bakal selalu ada buat kamu, kapan pun kamu butuh 🫶",
        
        "<br><b>Every day, I'm so proud of you</b>. Aku selalu jatuh cinta lagi dan lagi, dan ngobrol sama kamu tuh bikin aku seneng banget. Walaupun ngga tiap hari rasanya bahagia, tapi selama masih ada kamu, semuanya terasa lebih baik. Jadi, kalau ada apa-apa, cerita aja ke aku, jangan dipendem sendiri 🫠",
        
        "<br><b>Terakhir,</b><br>Aku sayaangg banget<br>samaa kamuu! 💐",
        
        "<br><i style='font-family: Satisfy, cursive; font-size: 21px; font-weight: 700;'>I lovee yoouu</i> 😍🫢💗"
    ];
    
    // Type title first
    typeText(titleElement, title, 27, () => {
        // Then type messages
        typeMessages(messageElement, messages, 0);
    });
}

function typeText(element, text, speed, callback) {
    let i = 0;
    const timer = setInterval(() => {
        if (i < text.length) {
            element.innerHTML += text.charAt(i);
            i++;
        } else {
            clearInterval(timer);
            if (callback) callback();
        }
    }, speed);
}

function typeMessages(element, messages, index) {
    if (index >= messages.length) {
        // Typing complete - change sticker
        setTimeout(() => {
            const mainSticker = document.getElementById('loveMainSticker');
            mainSticker.src = 'https://htmlku.com/0/panda/muah.gif';
        }, 1000);
        
        isLoveTyping = false;
        return;
    }
    
    const message = messages[index];
    let i = 0;
    
    const timer = setInterval(() => {
        if (i < message.length) {
            if (message.substr(i, 4) === '<br>') {
                element.innerHTML += '<br>';
                i += 4;
            } else if (message.substr(i, 3) === '<i ') {
                // Handle italic tags
                const endTag = message.indexOf('>', i);
                element.innerHTML += message.substring(i, endTag + 1);
                i = endTag + 1;
            } else if (message.substr(i, 4) === '</i>') {
                element.innerHTML += '</i>';
                i += 4;
            } else if (message.substr(i, 3) === '<b>') {
                element.innerHTML += '<b>';
                i += 3;
            } else if (message.substr(i, 4) === '</b>') {
                element.innerHTML += '</b>';
                i += 4;
            } else {
                element.innerHTML += message.charAt(i);
                i++;
            }
            
            // Auto scroll
            const container = document.querySelector('.love-container');
            container.scrollTop = container.scrollHeight;
        } else {
            clearInterval(timer);
            
            // Wait before next message
            setTimeout(() => {
                typeMessages(element, messages, index + 1);
            }, 800);
        }
    }, 20);
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    console.log('💌 Love Letter Game JS loaded!');
    
    // Cover screen click
    const loveCoverButton = document.getElementById('loveCoverButton');
    if (loveCoverButton) {
        loveCoverButton.addEventListener('click', startLoveLetterGame);
    }
    
    // Envelope click
    const loveEnvelope = document.getElementById('loveEnvelope');
    if (loveEnvelope) {
        loveEnvelope.addEventListener('click', openLoveLetter);
    }
    
    // Open button click
    const loveOpenBtn = document.getElementById('loveOpenBtn');
    if (loveOpenBtn) {
        loveOpenBtn.addEventListener('click', openLoveLetter);
    }
    
    // Modal close event
    const loveModal = document.getElementById('loveLetterGameModal');
    if (loveModal) {
        loveModal.addEventListener('hidden.bs.modal', function () {
            if (loveAudio) {
                loveAudio.pause();
                loveAudio.currentTime = 0;
            }
        });
        
        // Add click effect for love letter game
        loveModal.addEventListener('click', function(e) {
            const circle = document.createElement("div");
            circle.classList.add("love-click-effect");
            circle.style.left = `${e.pageX}px`;
            circle.style.top = `${e.pageY}px`;

            document.body.appendChild(circle);

            circle.addEventListener("animationend", () => {
                circle.remove();
            });
        });
    }
});
