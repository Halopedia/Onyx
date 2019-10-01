function onyx_toggleSidebar() {
  var sidebar = document.getElementById('onyx-pageBody-sidebar');
  if (sidebar.style.visibility === 'visible') {
    sidebar.style.visibility = 'collapse';
    console.log('Onyx: Collapsed sidebar');
  } else {
    sidebar.style.visibility = 'visible';
    console.log('Onyx: Expanded sidebar');
  }
}

function onyx_closeSiteNotice() {
  document.getElementById('onyx-content-siteNotice').remove();
  console.log('Onyx: Closed site notice')
}