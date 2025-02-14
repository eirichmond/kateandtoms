// JavaScript to handle scroll event

window.addEventListener("scroll", function () {

	const floater = document.getElementById("floater");
	const triggerHeight = 500; // Height in pixels where the class is added

	if (window.scrollY > triggerHeight) {

		floater.classList.add("active-float");
	} else {
		floater.classList.remove("active-float");
	}
});

