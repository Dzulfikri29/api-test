// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.
importScripts("https://www.gstatic.com/firebasejs/8.3.0/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.3.0/firebase-messaging.js");

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object
firebase.initializeApp({
    apiKey: "AIzaSyBbZ4BY7i07WW5-vupgF74sYTCFVYFmO84",
    authDomain: "ska-project.firebaseapp.com",
    projectId: "ska-project",
    storageBucket: "ska-project.appspot.com",
    messagingSenderId: "211957644514",
    appId: "1:211957644514:web:104ed4dd9b559bf42572bb",
    measurementId: "G-JP3Q6ML9B2",
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
messaging.onBackgroundMessage((payload) => {
    const noteTitle = payload.notification.title;
    const noteOptions = {
        body: payload.notification.body,
        icon: payload.notification.icon,
    };
    self.registration.showNotification(noteTitle, noteOptions);
    document.getElementById('alert-sound').play();
    if (payload.data.type == "messenger") {}
});
