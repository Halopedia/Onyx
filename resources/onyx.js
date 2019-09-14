function onyx_toggleSidebar() {
	var sidebar = document.getElementById("onyx-page-sidebar");
	if (sidebar.style.visibility === 'visible') {
		sidebar.style.display = 'collapse';
		console.log("Collapsed sidebar");
	} else {
		sidebar.style.display = 'visible';
		console.log("Expanded sidebar");
	}
}