<!-- cookies_banner.php -->
<div id="cookie-consent" style="position: fixed; bottom: 20px; right: 20px; max-width: 30rem; width: auto; background: rgba(255, 255, 255, 0.957); box-shadow: 0 10px 15px rgba(0,0,0,0.1); border-radius: 1.5rem; padding: 1rem; border: 1px solid #d1d5db; z-index: 90001; display: none;">
    <h2 style="font-weight: bold; font-size: 1.25rem; text-align: center; color: black;">
        We value your privacy
    </h2>
    <p style="font-size: 1rem; color: black; margin-top: 0.75rem; text-align: center;">
        We use cookies to enhance your browsing experience, serve personalised ads or content, and analyse our traffic. By clicking "Accept All", you consent to our use of cookies.
    </p>
    <div style="display: flex; flex-direction: row; gap: 0.75rem; margin-top: 1rem; justify-content: center; align-items: center;">
        <button class="btn border-3 fw-bold w-100 font-text" onclick="customizeCookies()" style="width: 100%; max-width: 10rem; background-color: #FFFFFF; border-color: #FD9830;">
            Customise
        </button>
        <button class="btn border-3 fw-bold w-100 font-text" onclick="rejectCookies()" style="width: 100%; max-width: 10rem; background-color: #FFFFFF; border-color: #FD9830; ">
            Reject all
        </button>
        <button class="btn text-white fw-bold w-100 font-text" onclick="acceptCookies()" style="width: 100%; max-width: 10rem; background-color: #FD9830;">
            Accept All
        </button>
    </div>
</div>

<style>
    @media (max-width: 576px) {
        .font-text {
            font-size: 12px;
        }
    }
</style>

<script>
let sessionId = document.cookie.match(/session_id=([^;]+)/)?.[1] || null;

function savePreference(preference) {
    const data = sessionId ? 'preference=' + preference + '&session_id=' + sessionId : 'preference=' + preference;
    console.log('Envoi de la requête avec:', data); // Log avant envoi
    fetch('<?php echo site_url('cookie/save_preference'); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        body: data
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur réseau: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Réponse de save_preference:', data); // Log de la réponse
        if (data.status === 'success') {
            if (data.session_id) {
                sessionId = data.session_id;
                document.cookie = 'session_id=' + sessionId + '; path=/; max-age=' + (365*24*60*60);
            }
            const cookieConsent = document.getElementById('cookie-consent');
            if (cookieConsent) {
                cookieConsent.style.display = 'none';
                console.log('Barre masquée');
            } else {
                console.error('Élément #cookie-consent non trouvé');
            }
        } else {
            console.error('Erreur dans la réponse:', data.message);
        }
    })
    .catch(error => console.error('Erreur dans save_preference:', error));
}

function acceptCookies() {
    savePreference('accepted');
}

function rejectCookies() {
    savePreference('rejected');
}

function customizeCookies() {
    alert('Customize cookies feature to be implemented');
}

window.onload = function() {
    fetch('<?php echo site_url('cookie/check_preference'); ?>')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Réponse check_preference:', data);
            if (!data.has_preference) {
                const cookieConsent = document.getElementById('cookie-consent');
                if (cookieConsent) {
                    cookieConsent.style.display = 'block';
                }
            }
            if (data.session_id) {
                sessionId = data.session_id;
            }
        })
        .catch(error => console.error('Erreur dans check_preference:', error));
};
</script>