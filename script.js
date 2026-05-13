document.addEventListener("DOMContentLoaded", () => {
    
    // 1. PROJELERİ ÇEK
    fetchProjects();

    // 2. İLETİŞİM FORMU AJAX İŞLEMİ
    const contactForm = document.getElementById('contact-form');
    
    // Eğer sayfada form varsa bu işlemi yap (hata vermemesi için kontrol)
    if(contactForm) {
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault(); 
            
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const message = document.getElementById('message').value;
            const errorDiv = document.getElementById('form-errors');
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(email)) {
                errorDiv.style.color = "#ff3366";
                errorDiv.innerText = "> ERROR: Geçersiz e-posta formatı!";
            } else {
                errorDiv.style.color = "var(--accent-yellow)";
                errorDiv.innerText = "> VERİ DOĞRULANDI. Ağ üzerinden aktarılıyor...";
                
                const payload = { name: name, email: email, message: message };

                fetch('save_message.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        errorDiv.style.color = "#00ffff"; 
                        errorDiv.innerText = "> MISSION SUCCESS: " + data.message;
                        contactForm.reset(); 
                    } else {
                        errorDiv.style.color = "#ff3366";
                        errorDiv.innerText = "> MISSION FAILED: " + data.message;
                    }
                })
                .catch(error => {
                    errorDiv.style.color = "#ff3366";
                    errorDiv.innerText = "> NETWORK ERROR: Sunucu ile iletişim koptu!";
                    console.error("Hata Detayı:", error);
                });
            }
        });
    }

    // 3. ANİMASYON GÖZLEMCİSİ (KAYDIRMA EFEKTİ VE BARLAR)
    const skillBars = document.querySelectorAll('.bar-fill');
    // Barların orijinal yüzdelerini HTML'den okuyup hafızaya al
    skillBars.forEach(bar => {
        bar.setAttribute('data-target-width', bar.style.width);
        bar.style.width = '0%'; // JS yüklenince sıfırla
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Bölüm ekrana girince 'visible' sınıfını ekle (CSS'teki animasyonu tetikler)
                if (entry.target.classList.contains('normal-section')) {
                    entry.target.classList.add('visible');
                }
                
                // Eğer bölüm Skills ise barları hedef genişliğe doğru doldur
                if (entry.target.id === 'skills') {
                    skillBars.forEach(bar => {
                        const targetWidth = bar.getAttribute('data-target-width');
                        bar.style.width = targetWidth; 
                    });
                }
            }
        });
    }, { threshold: 0.15 }); // %15'i görününce tetikle

    // Tüm normal bölümleri gözlemle
    document.querySelectorAll('.normal-section').forEach(section => {
        observer.observe(section);
    });

});

// --- PHP'DEN PROJELERİ ÇEKME FONKSİYONU ---
function fetchProjects() {
    const grid = document.getElementById('projects-grid');
    if(!grid) return; // Grid yoksa kodu durdur
    
    fetch('get_projects.php')
        .then(response => response.json())
        .then(data => {
            grid.innerHTML = ''; 

            data.forEach(project => {
                const card = document.createElement('div');
                card.style.border = "1px solid #444";
                card.style.padding = "25px";
                card.style.borderRadius = "8px";
                card.style.background = "#1a1a1a";
                card.style.transition = "transform 0.3s ease";
                
                card.addEventListener('mouseenter', () => card.style.transform = "translateY(-10px)");
                card.addEventListener('mouseleave', () => card.style.transform = "translateY(0)");

                const dateOnly = project.created_at.split(' ')[0];

                card.innerHTML = `
                    <h3 style="color: var(--accent-yellow); margin-bottom: 15px; font-size: 1.5rem;">${project.title}</h3>
                    <p style="font-size: 1rem; line-height: 1.6; color: #ccc;">${project.description}</p>
                    <small style="color: #666; display: block; margin-top: 20px; border-top: 1px solid #333; padding-top: 10px;">> Sistem Kaydı: ${dateOnly}</small>
                `;
                grid.appendChild(card);
            });
        })
        .catch(error => {
            console.error('Hata:', error);
            grid.innerHTML = '<p style="color:#ff3366;">Veritabanı bağlantısı koptu!</p>';
        });
}