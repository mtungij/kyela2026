
import "flowbite";

if ("serviceWorker" in navigator) {
	window.addEventListener("load", () => {
		navigator.serviceWorker.register("/sw.js").catch((error) => {
			console.error("Service worker registration failed:", error);
		});
	});
}

let deferredPrompt = null;

const setupPwaInstall = () => {
	const installButton = document.getElementById("pwa-install-btn");
	if (!installButton) {
		return;
	}

	installButton.addEventListener("click", async () => {
		if (!deferredPrompt) {
			return;
		}

		deferredPrompt.prompt();
		await deferredPrompt.userChoice;
		deferredPrompt = null;
		installButton.classList.add("hidden");
	});

	window.addEventListener("beforeinstallprompt", (event) => {
		event.preventDefault();
		deferredPrompt = event;
		installButton.classList.remove("hidden");
	});

	window.addEventListener("appinstalled", () => {
		deferredPrompt = null;
		installButton.classList.add("hidden");
	});
};

if (document.readyState === "loading") {
	document.addEventListener("DOMContentLoaded", setupPwaInstall);
} else {
	setupPwaInstall();
}
