////////////////////// import resources
import 'https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@3.0.1/dist/cookieconsent.umd.js';


const head = document.getElementsByTagName('head')[0];
const style = document.createElement('link');
style.rel = "stylesheet";
// cookieconsent custom styles
style.href = "/js/cookieconsent/cookieconsent.css";
head.insertBefore(style, head.firstChild);

document.documentElement.classList.add('cc--custom-theme');

//////////////////// categories config
const categories = {
    conf: {
        necessary: {
            readOnly: true
        },
        analytics: {
            enabled: true
        },
        ads: {
            enabled: true
        }
    },
    signals: {
        necessary: ['personalization_storage', 'functionality_storage', 'security_storage'],
        analytics: ['analytics_storage'],
        ads: ['ad_storage', 'ad_user_data', 'ad_personalization'],
    },
}

/////////////////// Handler of consent
const consentHandler = (cookie) => {
    const data = {};
    for (let categoryName in categories.signals) {
        let status = 'denied';
        if (cookie.categories.includes(categoryName)) status = 'granted';
        Object.assign(data, categories.signals[categoryName]
            .reduce((categorySignals, signal) =>
                ({...categorySignals, [signal]: status}), {}
            )
        );
    }
    gtag('consent', 'update', data);
    localStorage.setItem('consentMode', JSON.stringify(data))
}

//////////////////// Configure and run consent modal
CookieConsent.run({
    guiOptions: {
        consentModal: {
            layout: "bar",
            position: "bottom",
            equalWeightButtons: true,
            flipButtons: true
        },
        preferencesModal: {
            layout: "box",
            equalWeightButtons: true,
            flipButtons: true
        }
    },
    categories: categories.conf,
    language: {
        default: "en",
        autoDetect: "browser",
        translations: {
            "en": "/js/cookieconsent/lang/en.json",
            "uk": "/js/cookieconsent/lang/uk.json",
        }
    },
    disablePageInteraction: true,
    onConsent: ({cookie}) => consentHandler(cookie),
    onChange: ({cookie}) => consentHandler(cookie),
});
