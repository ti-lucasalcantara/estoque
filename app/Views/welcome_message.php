
<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>

		<!-- Meta data -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta content="Azea - Admin Panel HTML template" name="description">
		<meta content="Spruko Private Limited" name="author">
		<meta name="keywords" content="admin, admin template, dashboard, admin dashboard, responsive, bootstrap, bootstrap 5, admin theme, admin themes, bootstrap admin template, scss, ui, crm, modern, flat">

		<!-- Title -->
		<title>Azea - Admin Panel HTML template</title>

		<!--Favicon -->
		<link rel="icon" href="/assets/images/brand/favicon.ico" type="image/x-icon"/>

		<!--Bootstrap css -->
		<link id="style" href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

		<!-- Style css -->
		<link href="/assets/css/style.css" rel="stylesheet" />
		<link href="/assets/css/dark.css" rel="stylesheet" />
		<link href="/assets/css/skin-modes.css" rel="stylesheet" />

		<!-- Animate css -->
		<link href="/assets/css/animated.css" rel="stylesheet" />


		<!-- P-scroll bar css-->
		<link href="/assets/plugins/p-scrollbar/p-scrollbar.css" rel="stylesheet" />

		<!---Icons css-->
		<link href="/assets/css/icons.css" rel="stylesheet" />

	    <!-- Color Skin css -->
		<link id="theme" href="/assets/colors/color1.css" rel="stylesheet" type="text/css"/>

	</head>

	<body class="app sidebar-mini">

		<!---Global-loader-->
		<div id="global-loader" >
			<img src="/assets/images/svgs/loader.svg" alt="loader">
		</div>
		<!--- End Global-loader-->

		<!-- Page -->
		<div class="page">
			<div class="page-main">

				<!--aside open-->
				<aside class="app-sidebar">
					<div class="app-sidebar__logo">
						<a class="header-brand" href="index.html">
							<img src="/assets/images/brand/logo.png" class="header-brand-img desktop-lgo" alt="Azea logo">
							<img src="/assets/images/brand/logo1.png" class="header-brand-img dark-logo" alt="Azea logo">
							<img src="/assets/images/brand/favicon.png" class="header-brand-img mobile-logo" alt="Azea logo">
							<img src="/assets/images/brand/favicon1.png" class="header-brand-img darkmobile-logo" alt="Azea logo">
						</a>
					</div>

					<ul class="side-menu app-sidebar3">
						<li class="side-item side-item-category">Main</li>
						<li class="slide">
							<a class="side-menu__item"  href="index.html">
							<svg xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M3 13h1v7c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-7h1a1 1 0 0 0 .707-1.707l-9-9a.999.999 0 0 0-1.414 0l-9 9A1 1 0 0 0 3 13zm7 7v-5h4v5h-4zm2-15.586 6 6V15l.001 5H16v-5c0-1.103-.897-2-2-2h-4c-1.103 0-2 .897-2 2v5H6v-9.586l6-6z"/></svg>
							<span class="side-menu__label">Dashboard</span></a>
						</li>
						<li class="side-item side-item-category">Components</li>
						<li class="slide">
							<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
							<svg xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M12 22c4.879 0 9-4.121 9-9s-4.121-9-9-9-9 4.121-9 9 4.121 9 9 9zm0-16c3.794 0 7 3.206 7 7s-3.206 7-7 7-7-3.206-7-7 3.206-7 7-7zm5.284-2.293 1.412-1.416 3.01 3-1.413 1.417zM5.282 2.294 6.7 3.706l-2.99 3-1.417-1.413z"/><path d="M11 9h2v5h-2zm0 6h2v2h-2z"/></svg>
							<span class="side-menu__label">Utilities</span><i class="angle fe fe-chevron-right"></i></a>
							<ul class="slide-menu">
								<li><a href="elements-border.html" class="slide-item"> Border</a></li>
								<li><a href="element-colors.html" class="slide-item"> Colors</a></li>
								<li><a href="elements-display.html" class="slide-item"> Display</a></li>
								<li><a href="element-flex.html" class="slide-item"> Flex Items</a></li>
								<li><a href="element-height.html" class="slide-item"> Height</a></li>
								<li><a href="elements-margin.html" class="slide-item"> Margin</a></li>
								<li><a href="elements-paddning.html" class="slide-item"> Padding</a></li>
								<li><a href="element-typography.html" class="slide-item"> Typhography</a></li>
								<li><a href="element-width.html" class="slide-item"> Width</a></li>
							</ul>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
							<svg xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M20 17V7c0-2.168-3.663-4-8-4S4 4.832 4 7v10c0 2.168 3.663 4 8 4s8-1.832 8-4zM12 5c3.691 0 5.931 1.507 6 1.994C17.931 7.493 15.691 9 12 9S6.069 7.493 6 7.006C6.069 6.507 8.309 5 12 5zM6 9.607C7.479 10.454 9.637 11 12 11s4.521-.546 6-1.393v2.387c-.069.499-2.309 2.006-6 2.006s-5.931-1.507-6-2V9.607zM6 17v-2.393C7.479 15.454 9.637 16 12 16s4.521-.546 6-1.393v2.387c-.069.499-2.309 2.006-6 2.006s-5.931-1.507-6-2z"/></svg>
							<span class="side-menu__label">Elements</span><i class="angle fe fe-chevron-right"></i></a>
							<ul class="slide-menu">
								<li><a href="accordion.html" class="slide-item"> Accordion</a></li>
								<li><a href="alerts.html" class="slide-item"> Alerts</a></li>
								<li><a href="avatars.html" class="slide-item"> Avatars</a></li>
								<li><a href="badge.html" class="slide-item"> Badges</a></li>
								<li><a href="breadcrumbs.html" class="slide-item"> Breadcrumb</a></li>
								<li><a href="buttons.html" class="slide-item"> Buttons</a></li>
								<li><a href="cards.html" class="slide-item"> Cards</a></li>
								<li><a href="cards-image.html" class="slide-item"> Card Images</a></li>
								<li><a href="carousel.html" class="slide-item"> Carousel</a></li>
								<li><a href="dropdown.html" class="slide-item"> Dropdown</a></li>
								<li><a href="footers.html" class="slide-item"> Footers</a></li>
								<li><a href="list.html" class="slide-item"> List Group</a></li>
								<li><a href="media-object.html" class="slide-item"> Media Obejct</a></li>
								<li><a href="modal.html" class="slide-item"> Modal</a></li>
								<li><a href="navigation.html" class="slide-item"> Navigation</a></li>
								<li><a href="pagination.html" class="slide-item"> Pagination</a></li>
								<li><a href="panels.html" class="slide-item"> Panel</a></li>
								<li><a href="popover.html" class="slide-item"> Popover</a></li>
								<li><a href="progress.html" class="slide-item"> Progress</a></li>
								<li><a href="tabs.html" class="slide-item"> Tabs</a></li>
								<li><a href="toast.html" class="slide-item"> Toast</a></li>
								<li><a href="tags.html" class="slide-item"> Tags</a></li>
								<li><a href="tooltip.html" class="slide-item"> Tooltips</a></li>
							</ul>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
							<svg xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M10 3H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1zM9 9H5V5h4v4zm11-6h-6a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1zm-1 6h-4V5h4v4zm-9 4H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-6a1 1 0 0 0-1-1zm-1 6H5v-4h4v4zm8-6c-2.206 0-4 1.794-4 4s1.794 4 4 4 4-1.794 4-4-1.794-4-4-4zm0 6c-1.103 0-2-.897-2-2s.897-2 2-2 2 .897 2 2-.897 2-2 2z"/></svg>
							<span class="side-menu__label">Apps</span><i class="angle fe fe-chevron-right"></i></a>
							<ul class="slide-menu ">
								<li class="sub-slide">
									<a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Chat</span><i class="sub-angle fe fe-chevron-right"></i></a>
									<ul class="sub-slide-menu">
										<li><a class="sub-slide-item" href="chat.html">Chat 01</a></li>
										<li><a class="sub-slide-item" href="chat2.html">Chat 02</a></li>

									</ul>
								</li>
								<li class="sub-slide">
									<a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Contact</span><i class="sub-angle fe fe-chevron-right"></i></a>
									<ul class="sub-slide-menu">
										<li><a class="sub-slide-item" href="contact-list.html">Contact list 01</a></li>
										<li><a class="sub-slide-item" href="contact-list2.html">Contact list 02</a></li>
									</ul>
								</li>
								<li><a href="calendar.html" class="slide-item"> Calendar</a></li>
								<li><a href="cookies.html" class="slide-item"> Cookies</a></li>
								<li><a href="counters.html" class="slide-item"> Counters</a></li>
								<li><a href="dragula.html" class="slide-item"> Dragula Card</a></li>
								<li class="sub-slide">
									<a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">File Manager</span><i class="sub-angle fe fe-chevron-right"></i></a>
									<ul class="sub-slide-menu">
										<li><a class="sub-slide-item" href="file-manager.html">File Manager 01</a></li>
										<li><a class="sub-slide-item" href="file-manager-list.html">File Manager 02</a></li>
									</ul>
								</li>
								<li><a href="image-comparison.html" class="slide-item"> Image Comparison</a></li>
								<li><a href="img-crop.html" class="slide-item"> Image Crop</a></li>
								<li><a href="loaders.html" class="slide-item"> Loaders</a></li>
								<li><a href="notify.html" class="slide-item"> Notifications</a></li>
								<li><a href="page-sessiontimeout.html" class="slide-item"> Page-sessiontimeout</a></li>
								<li><a href="rangeslider.html" class="slide-item"> Range slider</a></li>
								<li><a href="rating.html" class="slide-item"> Rating</a></li>
								<li><a href="sweetalert.html" class="slide-item"> Sweet alerts</a></li>
								<li class="sub-slide">
									<a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Todo List</span><i class="sub-angle fe fe-chevron-right"></i></a>
									<ul class="sub-slide-menu">
										<li><a class="sub-slide-item" href="todo-list.html">Todo List 01</a></li>
										<li><a class="sub-slide-item" href="todo-list2.html">Todo List 02</a></li>
										<li><a class="sub-slide-item" href="todo-list3.html">Todo List 03</a></li>
									</ul>
								</li>
								<li><a href="time-line.html" class="slide-item"> Time Line</a></li>
								<li class="sub-slide">
									<a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">User List</span><i class="sub-angle fe fe-chevron-right"></i></a>
									<ul class="sub-slide-menu">
										<li><a class="sub-slide-item" href="users-list-1.html">User List 01</a></li>

										<li><a class="sub-slide-item" href="users-list-3.html">User List 03</a></li>

									</ul>
								</li>
							</ul>
						</li>
						<li class="side-item side-item-category">Tables & Icons </li>
						<li class="slide">
							<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
							<svg xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M19 3H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2zm0 2 .001 4H5V5h14zM5 11h8v8H5v-8zm10 8v-8h4.001l.001 8H15z"/></svg>
							<span class="side-menu__label">Tables</span><i class="angle fe fe-chevron-right"></i></a>
							<ul class="slide-menu">
								<li><a href="tables.html" class="slide-item">Default table</a></li>
								<li><a href="datatable.html" class="slide-item">Data Table</a></li>
							</ul>
						</li>
						<li class="slide">
						    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
							<svg xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M20 7h-1.209A4.92 4.92 0 0 0 19 5.5C19 3.57 17.43 2 15.5 2c-1.622 0-2.705 1.482-3.404 3.085C11.407 3.57 10.269 2 8.5 2 6.57 2 5 3.57 5 5.5c0 .596.079 1.089.209 1.5H4c-1.103 0-2 .897-2 2v2c0 1.103.897 2 2 2v7c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-7c1.103 0 2-.897 2-2V9c0-1.103-.897-2-2-2zm-4.5-3c.827 0 1.5.673 1.5 1.5C17 7 16.374 7 16 7h-2.478c.511-1.576 1.253-3 1.978-3zM7 5.5C7 4.673 7.673 4 8.5 4c.888 0 1.714 1.525 2.198 3H8c-.374 0-1 0-1-1.5zM4 9h7v2H4V9zm2 11v-7h5v7H6zm12 0h-5v-7h5v7zm-5-9V9.085L13.017 9H20l.001 2H13z"/></svg>
							<span class="side-menu__label">Icons</span><span class="badge bg-danger  side-badge">11</span></a>
							<ul class="slide-menu">
								<li><a href="icons.html" class="slide-item"> Font Awesome</a></li>
								<li><a href="icons2.html" class="slide-item"> Material Design Icons</a></li>
								<li><a href="icons3.html" class="slide-item"> Simple Line Icons</a></li>
								<li><a href="icons4.html" class="slide-item"> Feather Icons</a></li>
								<li><a href="icons5.html" class="slide-item"> Ionic Icons</a></li>
								<li><a href="icons6.html" class="slide-item"> Flag Icons</a></li>
								<li><a href="icons7.html" class="slide-item"> pe7 Icons</a></li>
								<li><a href="icons8.html" class="slide-item"> Themify Icons</a></li>
								<li><a href="icons9.html" class="slide-item">Typicons Icons</a></li>
								<li><a href="icons10.html" class="slide-item">Weather Icons</a></li>
								<li><a href="icons11.html" class="slide-item">Material Svgs</a></li>
								<li><a href="icons12.html" class="slide-item">Bootstrap Svgs</a></li>
								</ul>
						</li>
						<li class="side-item side-item-category">Widgets & Maps</li>
						<li class="slide">
							<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
						   <svg xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M10 3H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1zM9 9H5V5h4v4zm11 4h-6a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-6a1 1 0 0 0-1-1zm-1 6h-4v-4h4v4zM17 3c-2.206 0-4 1.794-4 4s1.794 4 4 4 4-1.794 4-4-1.794-4-4-4zm0 6c-1.103 0-2-.897-2-2s.897-2 2-2 2 .897 2 2-.897 2-2 2zM7 13c-2.206 0-4 1.794-4 4s1.794 4 4 4 4-1.794 4-4-1.794-4-4-4zm0 6c-1.103 0-2-.897-2-2s.897-2 2-2 2 .897 2 2-.897 2-2 2z"/></svg>
							<span class="side-menu__label">Widgets</span><i class="angle fe fe-chevron-right"></i></a>
							<ul class="slide-menu ">
								<li><a href="widgets-2.html" class="slide-item">Chart Widgets</a></li>
								<li><a href="widgets-1.html" class="slide-item">Widgets</a></li>
							</ul>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
							<svg xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="m21.447 6.105-6-3a1 1 0 0 0-.895 0L9 5.882 3.447 3.105A1 1 0 0 0 2 4v13c0 .379.214.725.553.895l6 3a1 1 0 0 0 .895 0L15 18.118l5.553 2.776a.992.992 0 0 0 .972-.043c.295-.183.475-.504.475-.851V7c0-.379-.214-.725-.553-.895zM10 7.618l4-2v10.764l-4 2V7.618zm-6-2 4 2v10.764l-4-2V5.618zm16 12.764-4-2V5.618l4 2v10.764z"/></svg>
							<span class="side-menu__label">Map</span><i class="angle fe fe-chevron-right"></i></a>
							<ul class="slide-menu">
								<li><a href="maps2.html" class="slide-item">Leaflet Maps</a></li>
								<li><a href="maps.html" class="slide-item">Vector Maps</a></li>
							</ul>
						</li>
						<li class="side-item side-item-category">Forms & Charts </li>
						<li class="slide">
							<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
							<svg xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M19.937 8.68c-.011-.032-.02-.063-.033-.094a.997.997 0 0 0-.196-.293l-6-6a.997.997 0 0 0-.293-.196c-.03-.014-.062-.022-.094-.033a.991.991 0 0 0-.259-.051C13.04 2.011 13.021 2 13 2H6c-1.103 0-2 .897-2 2v16c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2V9c0-.021-.011-.04-.013-.062a.99.99 0 0 0-.05-.258zM16.586 8H14V5.414L16.586 8zM6 20V4h6v5a1 1 0 0 0 1 1h5l.002 10H6z"/></svg>
							<span class="side-menu__label">Forms</span><span class="badge bg-success side-badge">6</span></a>
							<ul class="slide-menu">
								<li><a href="form-elements.html" class="slide-item"> Form Elements</a></li>
								<li><a href="advanced-forms.html" class="slide-item"> Advanced Forms</a></li>
								<li><a href="form-wizard.html" class="slide-item"> Form Wizard</a></li>
								<li><a href="form-editor.html" class="slide-item"> Form Editor</a></li>
								<li><a href="form-sizes.html" class="slide-item"> Form Element Sizes</a></li>
								<li><a href="form-treeview.html" class="slide-item"> Form Treeview</a></li>
								<li><a href="form-validations.html" class="slide-item"> Form Validations</a></li>
							</ul>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
							<svg xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M20 7h-4V4c0-1.103-.897-2-2-2h-4c-1.103 0-2 .897-2 2v5H4c-1.103 0-2 .897-2 2v9a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V9c0-1.103-.897-2-2-2zM4 11h4v8H4v-8zm6-1V4h4v15h-4v-9zm10 9h-4V9h4v10z"/></svg>
							<span class="side-menu__label">Charts</span><span class="badge bg-info side-badge">7</span></a>
							<ul class="slide-menu">
								<li><a href="chart-apex.html" class="slide-item"> Apex Charts</a></li>
								<li><a href="chart-chartist.html" class="slide-item">Chartjs Charts</a></li>
								<li><a href="chart-echart.html" class="slide-item"> Echart Charts</a></li>
								<li><a href="chart-flot.html" class="slide-item"> Flot Charts</a></li>
								<li><a href="chart-morris.html" class="slide-item"> Morris Charts</a></li>
								<li><a href="chart-peity.html" class="slide-item"> Pie Charts</a></li>
								<li><a href="chart-c3.html" class="slide-item">C3 Charts</a></li>
							</ul>
						</li>
						<li class="side-item side-item-category">Custom  & Error Pages</li>
						<li class="slide">
							<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
							<svg xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M3 11h8V3H3zm2-6h4v4H5zM3 21h8v-8H3zm2-6h4v4H5zm8-12v8h8V3zm6 6h-4V5h4zm-5.99 4h2v2h-2zm2 2h2v2h-2zm-2 2h2v2h-2zm4 0h2v2h-2zm2 2h2v2h-2zm-4 0h2v2h-2zm2-6h2v2h-2zm2 2h2v2h-2z"/></svg>
							<span class="side-menu__label">Custom Pages</span><i class="angle fe fe-chevron-right"></i></a>
							<ul class="slide-menu">
								<li class="sub-slide">
									<a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Register</span><i class="sub-angle fe fe-chevron-right"></i></a>
									<ul class="sub-slide-menu">
										<li><a class="sub-slide-item" href="register-1.html">Register 01</a></li>
										<li><a class="sub-slide-item" href="register-2.html">Register 02</a></li>
										<li><a class="sub-slide-item" href="register-3.html">Register 03</a></li>
									</ul>
								</li>
								<li class="sub-slide">
									<a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Login</span><i class="sub-angle fe fe-chevron-right"></i></a>
									<ul class="sub-slide-menu">
										<li><a class="sub-slide-item" href="login-1.html">Login 01</a></li>
										<li><a class="sub-slide-item" href="login-2.html">Login 02</a></li>
										<li><a class="sub-slide-item" href="login-3.html">Login 03</a></li>
									</ul>
								</li>
								<li class="sub-slide">
									<a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Forget Password</span><i class="sub-angle fe fe-chevron-right"></i></a>
									<ul class="sub-slide-menu">
										<li><a class="sub-slide-item" href="forgot-password-1.html">Forget Password 01</a></li>
										<li><a class="sub-slide-item" href="forgot-password-2.html">Forget Password 02</a></li>
										<li><a class="sub-slide-item" href="forgot-password-3.html">Forget Password 03</a></li>
									</ul>
								</li>
								<li class="sub-slide">
									<a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Reset Password</span><i class="sub-angle fe fe-chevron-right"></i></a>
									<ul class="sub-slide-menu">
										<li><a class="sub-slide-item" href="reset-password-1.html">Reset Password 01</a></li>
										<li><a class="sub-slide-item" href="reset-password-2.html">Reset Password 02</a></li>
										<li><a class="sub-slide-item" href="reset-password-3.html">Reset Password 03</a></li>
									</ul>
								</li>
								<li class="sub-slide">
									<a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Lock Screen</span><i class="sub-angle fe fe-chevron-right"></i></a>
									<ul class="sub-slide-menu">
										<li><a class="sub-slide-item" href="lockscreen-1.html">Lock Screen 01</a></li>
										<li><a class="sub-slide-item" href="lockscreen-2.html">Lock Screen 02</a></li>
										<li><a class="sub-slide-item" href="lockscreen-3.html">Lock Screen 03</a></li>
									</ul>
								</li>
								<li><a href="construction.html" class="slide-item"> Under Construction</a></li>
								<li><a href="coming.html" class="slide-item"> Coming Soon</a></li>
							</ul>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
							<svg xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M16 2H8C4.691 2 2 4.691 2 8v13a1 1 0 0 0 1 1h13c3.309 0 6-2.691 6-6V8c0-3.309-2.691-6-6-6zm4 14c0 2.206-1.794 4-4 4H4V8c0-2.206 1.794-4 4-4h8c2.206 0 4 1.794 4 4v8z"/><path d="M11 6h2v8h-2zm0 10h2v2h-2z"/></svg>
							<span class="side-menu__label">Error Pages</span><i class="angle fe fe-chevron-right"></i></a>
							<ul class="slide-menu">
								<li><a href="400.html" class="slide-item"> 400</a></li>
								<li><a href="401.html" class="slide-item"> 401</a></li>
								<li><a href="403.html" class="slide-item"> 403</a></li>
								<li><a href="404.html" class="slide-item"> 404</a></li>
								<li><a href="500.html" class="slide-item"> 500</a></li>
								<li><a href="503.html" class="slide-item"> 503</a></li>
							</ul>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
							<svg xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/></svg>
							<span class="side-menu__label">Sub Menus</span><i class="angle fe fe-chevron-right"></i></a>
							<ul class="slide-menu ">
								<li class="sub-slide">
									<a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Submenu 1</span><i class="sub-angle fe fe-chevron-right"></i></a>
									<ul class="sub-slide-menu">
										<li><a class="sub-slide-item" href="javascript:void(0);">Submenu 1.1</a></li>
										<li class="sub-slide2">
											<a class="sub-slide-item2"  data-bs-toggle="sub-slide2" href="javascript:void(0);"><span class="sub-side-menu__label2">Submenu 1.2</span><i class="sub-angle2 fe fe-chevron-right"></i></a>
											<ul class="sub-slide-menu2">
												<li><a href="javascript:void(0);" class="sub-slide-item2">Submenu 1.2.1</a></li>
												<li><a href="javascript:void(0);" class="sub-slide-item2">Submenu 1.2.2</a></li>
												<li><a href="javascript:void(0);" class="sub-slide-item2">Submenu 1.2.3</a></li>
											</ul>
										</li>
										<li><a class="sub-slide-item" href="javascript:void(0);">Submenu 1.3</a></li>
									</ul>
								</li>
								<li><a href="javascript:void(0);" class="slide-item">Submenu 2</a></li>
								<li><a href="javascript:void(0);" class="slide-item">Submenu 3</a></li>
								<li><a href="javascript:void(0);" class="slide-item">Submenu 4</a></li>
							</ul>
						</li>
						<li class="side-item side-item-category">Pages & E-Commerce</li>
						<li class="slide">
							<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
							<svg xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M22 7.999a1 1 0 0 0-.516-.874l-9.022-5a1.003 1.003 0 0 0-.968 0l-8.978 4.96a1 1 0 0 0-.003 1.748l9.022 5.04a.995.995 0 0 0 .973.001l8.978-5A1 1 0 0 0 22 7.999zm-9.977 3.855L5.06 7.965l6.917-3.822 6.964 3.859-6.918 3.852z"/><path d="M20.515 11.126 12 15.856l-8.515-4.73-.971 1.748 9 5a1 1 0 0 0 .971 0l9-5-.97-1.748z"/><path d="M20.515 15.126 12 19.856l-8.515-4.73-.971 1.748 9 5a1 1 0 0 0 .971 0l9-5-.97-1.748z"/></svg>
							<span class="side-menu__label">Pages</span><i class="angle fe fe-chevron-right"></i></a>
							<ul class="slide-menu">
								<li class="sub-slide">
									<a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Profile</span><i class="sub-angle fe fe-chevron-right"></i></a>
									<ul class="sub-slide-menu">
										<li><a class="sub-slide-item" href="profile-1.html">Profile 01</a></li>
										<li><a class="sub-slide-item" href="profile-2.html">Profile 02</a></li>
										<li><a class="sub-slide-item" href="profile-3.html">Profile 03</a></li>
									</ul>
								</li>
								<li><a href="editprofile.html" class="slide-item"> Edit Profile</a></li>
								<li class="sub-slide">
									<a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Email</span><i class="sub-angle fe fe-chevron-right"></i></a>
									<ul class="sub-slide-menu">
										<li><a class="sub-slide-item" href="email-compose.html">Email Compose</a></li>
										<li><a class="sub-slide-item" href="email-inbox.html">Email Inbox</a></li>
										<li><a class="sub-slide-item" href="email-read.html">Email Read</a></li>
									</ul>
								</li>
								<li class="sub-slide">
									<a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Pricing</span><i class="sub-angle fe fe-chevron-right"></i></a>
									<ul class="sub-slide-menu">
										<li><a class="sub-slide-item" href="pricing.html">Pricing 01</a></li>
										<li><a class="sub-slide-item" href="pricing-2.html">Pricing 02</a></li>
										<li><a class="sub-slide-item" href="pricing-3.html">Pricing 03</a></li>
									</ul>
								</li>
								<li class="sub-slide">
									<a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Invoice</span><i class="sub-angle fe fe-chevron-right"></i></a>
									<ul class="sub-slide-menu">
										<li><a class="sub-slide-item" href="invoice-list.html">Invoice list</a></li>
										<li><a class="sub-slide-item" href="invoice-1.html">Invoice 01</a></li>
										<li><a class="sub-slide-item" href="invoice-2.html">Invoice 02</a></li>
										<li><a class="sub-slide-item" href="invoice-3.html">Invoice 03</a></li>
										<li><a class="sub-slide-item" href="invoice-add.html">Add Invoice</a></li>
										<li><a class="sub-slide-item" href="invoice-edit.html">Edit Invoice</a></li>
									</ul>
								</li>
								<li class="sub-slide">
									<a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Blog</span><i class="sub-angle fe fe-chevron-right"></i></a>
									<ul class="sub-slide-menu">
										<li><a class="sub-slide-item" href="blog.html">Blog 01</a></li>
										<li><a class="sub-slide-item" href="blog-2.html">Blog 02</a></li>
										<li><a class="sub-slide-item" href="blog-3.html">Blog 03</a></li>
										<li><a class="sub-slide-item" href="blog-styles.html">Blog Styles</a></li>
									</ul>
								</li>
								<li><a href="gallery.html" class="slide-item"> Gallery</a></li>
								<li><a href="faq.html" class="slide-item"> FAQS</a></li>
								<li><a href="terms.html" class="slide-item"> Terms</a></li>
								<li><a href="search.html" class="slide-item"> Search</a></li>
								<li><a href="empty.html" class="slide-item"> Empty Page</a></li>
                                 <li><a href="switcher.html" class="slide-item"> Switcher</a></li>
                                <li><a href="switcher1.html" class="slide-item"> Switcher1</a></li>
							</ul>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
							<svg xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M5 22h14c1.103 0 2-.897 2-2V9a1 1 0 0 0-1-1h-3V7c0-2.757-2.243-5-5-5S7 4.243 7 7v1H4a1 1 0 0 0-1 1v11c0 1.103.897 2 2 2zM9 7c0-1.654 1.346-3 3-3s3 1.346 3 3v1H9V7zm-4 3h2v2h2v-2h6v2h2v-2h2l.002 10H5V10z"/></svg>
							<span class="side-menu__label">E-Commerce</span><span class="badge bg-secondary side-badge">5</span></a>
							<ul class="slide-menu">
								<li><a href="shop.html" class="slide-item"> Products</a></li>
								<li><a href="shop-des.html" class="slide-item">Product Details</a></li>
								<li><a href="cart.html" class="slide-item"> Shopping Cart</a></li>
                                 <li><a href="checkout.html" class="slide-item"> Checkout</a></li>
								<li><a href="wishlist.html" class="slide-item">wishlist</a></li>
							</ul>
						</li>
					</ul>
				</aside>
				<!--aside closed-->

				<!-- App-Content -->
				<div class="app-content main-content">
					<div class="side-app">

						<!--app header-->
					<div class="app-header header main-header1">
						<div class="container-fluid">
							<div class="d-flex">
								<a class="header-brand" href="index.html">
									<img src="/assets/images/brand/logo.png" class="header-brand-img desktop-lgo" alt="Azea logo">
									<img src="/assets/images/brand/logo1.png" class="header-brand-img dark-logo" alt="Azea logo">
									<img src="/assets/images/brand/favicon.png" class="header-brand-img mobile-logo" alt="Azea logo">
									<img src="/assets/images/brand/favicon1.png" class="header-brand-img darkmobile-logo" alt="Azea logo">
								</a>
								<div class="app-sidebar__toggle d-flex" data-bs-toggle="sidebar">
									<a class="open-toggle" href="javascript:void(0);">
										<svg xmlns="http://www.w3.org/2000/svg" class="feather feather-align-left header-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/></svg>
									</a>
								</div>
								<div class="mt-1 d-md-block d-none">
									<form class="form-inline">
										<div class="search-element">
											<input type="search" class="form-control header-search mobile-view-search" placeholder="Search…" aria-label="Search" tabindex="1">
											<button class="btn btn-primary-color" type="submit">
											<svg xmlns="http://www.w3.org/2000/svg" class="header-icon search-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z"/></svg>
											</button>

										</div>
									</form>
								</div><!-- SEARCH -->
								<div class="d-flex order-lg-2 ms-auto main-header-end">
									<button  class="navbar-toggler navresponsive-toggler d-md-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="true" aria-label="Toggle navigation">
										<i class="fe fe-more-vertical header-icons navbar-toggler-icon"></i>
									</button>
									<div class="navbar navbar-expand-lg navbar-collapse responsive-navbar p-0">
										<div class="collapse navbar-collapse" id="navbarSupportedContent-4">
											<div class="d-flex order-lg-2">
												<div class="dropdown d-lg-none d-flex responsive-search">
													<a href="javascript:void(0);" class="nav-link icon" data-bs-toggle="dropdown">
														<svg xmlns="http://www.w3.org/2000/svg" class="header-icon search-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z"/></svg>
													</a>
													<div class="dropdown-menu header-search dropdown-menu-start">
														<div class="input-group w-100 p-2">
															<input type="text" class="form-control" placeholder="Search....">
															<button class="btn btn-primary-color" type="submit">
																<svg class="header-icon search-icon p-1 mt-1" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
															</button>
														</div>
													</div>
												</div><!-- SEARCH -->
												<div class="dropdown d-flex">
													<a class="nav-link icon theme-layout nav-link-bg layout-setting">
														<span class="light-layout"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M20.742 13.045a8.088 8.088 0 0 1-2.077.271c-2.135 0-4.14-.83-5.646-2.336a8.025 8.025 0 0 1-2.064-7.723A1 1 0 0 0 9.73 2.034a10.014 10.014 0 0 0-4.489 2.582c-3.898 3.898-3.898 10.243 0 14.143a9.937 9.937 0 0 0 7.072 2.93 9.93 9.93 0 0 0 7.07-2.929 10.007 10.007 0 0 0 2.583-4.491 1.001 1.001 0 0 0-1.224-1.224zm-2.772 4.301a7.947 7.947 0 0 1-5.656 2.343 7.953 7.953 0 0 1-5.658-2.344c-3.118-3.119-3.118-8.195 0-11.314a7.923 7.923 0 0 1 2.06-1.483 10.027 10.027 0 0 0 2.89 7.848 9.972 9.972 0 0 0 7.848 2.891 8.036 8.036 0 0 1-1.484 2.059z"/></svg></span>
														<span class="dark-layout"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M6.993 12c0 2.761 2.246 5.007 5.007 5.007s5.007-2.246 5.007-5.007S14.761 6.993 12 6.993 6.993 9.239 6.993 12zM12 8.993c1.658 0 3.007 1.349 3.007 3.007S13.658 15.007 12 15.007 8.993 13.658 8.993 12 10.342 8.993 12 8.993zM10.998 19h2v3h-2zm0-17h2v3h-2zm-9 9h3v2h-3zm17 0h3v2h-3zM4.219 18.363l2.12-2.122 1.415 1.414-2.12 2.122zM16.24 6.344l2.122-2.122 1.414 1.414-2.122 2.122zM6.342 7.759 4.22 5.637l1.415-1.414 2.12 2.122zm13.434 10.605-1.414 1.414-2.122-2.122 1.414-1.414z"/></svg></span>
													</a>
												</div><!-- Theme-Layout -->
												<div class="dropdown   header-fullscreen d-flex" >
													<a  class="nav-link icon full-screen-link p-0"  id="fullscreen-button">
													<svg xmlns="http://www.w3.org/2000/svg" class="header-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M5 5h5V3H3v7h2zm5 14H5v-5H3v7h7zm11-5h-2v5h-5v2h7zm-2-4h2V3h-7v2h5z"/></svg>
													</a>
												</div>
												<div class="dropdown country-selector d-flex">
												<a href="javascript:void(0);" class="nav-link leading-none" data-bs-toggle="dropdown">
													<span class="header-avatar1">
														<img src="/assets/images/us_flag.jpg" alt="img" class="me-2 country">
														<span class="fs-14 font-weight-semibold"> English</span>
													</span>
												</a>
												<div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow animated">
													<a class="dropdown-item d-flex" href="profile-1.html">
														<img src="/assets/images/germany_flag.jpg" alt="img" class="me-2 country mt-1">
														<span class="fs-13"> Germany</span>
													</a>
													<a class="dropdown-item d-flex" href="search.html">
														<img src="/assets/images/italy_flag.jpg" alt="img" class="me-2 country mt-1">
														<span class="fs-13"> Italy</span>
													</a>
													<a class="dropdown-item d-flex" href="chat.html">
														<img src="/assets/images/russia_flag.jpg" alt="img" class="me-2 country mt-1">
														<span class="fs-13"> Russia</span>
													</a>
													<a class="dropdown-item d-flex" href="login-1.html">
														<img src="/assets/images/spain_flag.jpg" alt="img" class=" me-2 country mt-1">
														<span class="fs-13"> Spain</span>
													</a>
												</div>
											</div>
											<div class="dropdown header-message d-flex">
													<a class="nav-link icon" data-bs-toggle="dropdown">
														<svg xmlns="http://www.w3.org/2000/svg" class="header-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M20 4H4c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2zm0 2v.511l-8 6.223-8-6.222V6h16zM4 18V9.044l7.386 5.745a.994.994 0 0 0 1.228 0L20 9.044 20.002 18H4z"/></svg>
														<span class="badge bg-success side-badge">5</span>
													</a>
													<div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow  animated">
														<div class="dropdown-header">
															<h6 class="mb-0">Messages</h6>
															<span class="badge fs-10 bg-secondary br-7 ms-auto">New</span>
														</div>
														<div class="header-dropdown-list message-menu">
															<a class="dropdown-item border-bottom" href="chat.html">
																<div class="d-flex align-items-center">
																	<div class="">
																		<span class="avatar avatar-md brround align-self-center cover-image" data-image-src="/assets/images/users/1.jpg"></span>
																	</div>
																	<div class="d-flex mt-1 mb-1">
																		<div class="ps-3">
																			<span class="mb-1 fs-13">Joan Powell</span>
																			<p class="fs-12 mb-1">All the best your template awesome</p>
																			<div class="fs-11 text-muted">
																				3 hours ago
																			</div>
																		</div>
																	</div>
																</div>
															</a>
															<a class="dropdown-item border-bottom" href="chat.html">
																<div class="d-flex align-items-center">
																	<div class="">
																		<span class="avatar avatar-md brround align-self-center cover-image" data-image-src="/assets/images/users/2.jpg"></span>
																	</div>
																	<div class="d-flex mt-1 mb-1">
																		<div class="ps-3">
																			<span class="mb-1 s-13">Gavin Sibson</span>
																			<p class="fs-12 mb-1">Hey! there I'm available</p>
																			<div class="fs-11 text-muted">
																				5 hour ago
																			</div>
																		</div>
																	</div>
																</div>
															</a>
															<a class="dropdown-item border-bottom" href="chat.html">
																<div class="d-flex align-items-center">
																	<div class="">
																		<span class="avatar avatar-md brround align-self-center cover-image" data-image-src="/assets/images/users/3.jpg"></span>
																	</div>
																	<div class="d-flex mt-1 mb-1">
																		<div class="ps-3">
																			<span class="mb-1">Julian Kerr</span>
																			<p class="fs-12 mb-1">Just created a new blog post</p>
																			<div class="fs-11 text-muted">
																				45 mintues ago
																			</div>
																		</div>
																	</div>
																</div>
															</a>
															<a class="dropdown-item border-bottom" href="chat.html">
																<div class="d-flex align-items-center">
																	<div class="">
																		<span class="avatar avatar-md brround align-self-center cover-image" data-image-src="/assets/images/users/4.jpg"></span>
																	</div>
																	<div class="d-flex mt-1 mb-1">
																		<div class="ps-3">
																			<span class=" fs-13 mb-1">Cedric Kelly</span>
																			<p class="fs-12 mb-1">Added new comment on your photo</p>
																			<div class="fs-11 text-muted">
																				2 days ago
																			</div>
																		</div>
																	</div>
																</div>
															</a>
															<a class="dropdown-item border-bottom"  href="chat.html">
																<div class="d-flex align-items-center">
																	<div class="">
																		<span class="avatar avatar-md brround align-self-center cover-image" data-image-src="/assets/images/users/6.jpg"></span>
																	</div>
																	<div class="d-flex mt-1 mb-1">
																		<div class="ps-3">
																			<span class="mb-1 fs-13">Julian Kerr</span>
																			<p class="fs-12 mb-1">Your payment invoice is generated</p>
																			<div class="fs-11 text-muted">
																				3 days ago
																			</div>
																		</div>
																	</div>
																</div>
															</a>
															<a class="dropdown-item" href="chat.html">
																<div class="d-flex align-items-center">
																	<div class="">
																		<span class="avatar avatar-md brround align-self-center cover-image" data-image-src="/assets/images/users/7.jpg"></span>
																	</div>
																	<div class="d-flex mt-1 mb-1">
																		<div class="ps-3">
																			<span class="mb-1 fs-13">Faith Dickens</span>
																			<p class="fs-12 mb-1">Please check your mail....</p>
																			<div class="fs-11 text-muted">
																				4 days ago
																			</div>
																		</div>
																	</div>
																</div>
															</a>
														</div>
														<div class=" text-center p-2 pt-3 border-top">
															<a href="chat.html" class="fs-13 btn btn-primary btn-md btn-block">See More</a>
														</div>
													</div>
												</div>
												<div class="dropdown header-notify d-flex">
													<a class="nav-link icon" data-bs-toggle="dropdown">
														<svg xmlns="http://www.w3.org/2000/svg" class="header-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M19 13.586V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.074 5 6.783 5 10v3.586l-1.707 1.707A.996.996 0 0 0 3 16v2a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-2a.996.996 0 0 0-.293-.707L19 13.586zM19 17H5v-.586l1.707-1.707A.996.996 0 0 0 7 14v-4c0-2.757 2.243-5 5-5s5 2.243 5 5v4c0 .266.105.52.293.707L19 16.414V17zm-7 5a2.98 2.98 0 0 0 2.818-2H9.182A2.98 2.98 0 0 0 12 22z"/></svg><span class="pulse "></span>
													</a>
													<div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow  animated">
														<div class="dropdown-header">
															<h6 class="mb-0">Notifications</h6>
															<span class="badge fs-10 bg-secondary br-7 ms-auto">New</span>
														</div>
														<div class="notify-menu">
															<a href="email-inbox.html" class="dropdown-item border-bottom d-flex ps-4">
																<div class="notifyimg  text-primary bg-primary-transparent border-primary"> <i class="fa fa-envelope"></i> </div>
																<div>
																	<span class="fs-13">Message Sent.</span>
																	<div class="small text-muted">3 hours ago</div>
																</div>
															</a>
															<a href="email-inbox.html" class="dropdown-item border-bottom d-flex ps-4">
																<div class="notifyimg  text-secondary bg-secondary-transparent border-secondary"> <i class="fa fa-shopping-cart"></i></div>
																<div>
																	<span class="fs-13">Order Placed</span>
																	<div class="small text-muted">5  hour ago</div>
																</div>
															</a>
															<a href="email-inbox.html" class="dropdown-item border-bottom d-flex ps-4">
																<div class="notifyimg  text-danger bg-danger-transparent border-danger"> <i class="fa fa-gift"></i> </div>
																<div>
																	<span class="fs-13">Event Started</span>
																	<div class="small text-muted">45 mintues ago</div>
																</div>
															</a>
															<a href="email-inbox.html" class="dropdown-item border-bottom d-flex ps-4 mb-2">
																<div class="notifyimg  text-success  bg-success-transparent border-success"> <i class="fa fa-windows"></i> </div>
																<div>
																	<span class="fs-13">Your Admin lanuched</span>
																	<div class="small text-muted">1 daya ago</div>
																</div>
															</a>
														</div>
														<div class=" text-center p-2">
															<a href="email-inbox.html" class="btn btn-primary btn-md fs-13 btn-block">View All</a>
														</div>
													</div>
												</div>
												<div class="dropdown profile-dropdown d-flex">
													<a href="javascript:void(0);" class="nav-link pe-0 leading-none" data-bs-toggle="dropdown">
														<span class="header-avatar1">
															<img src="/assets/images/users/2.jpg" alt="img" class="avatar avatar-md brround">
														</span>
													</a>
													<div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow animated">
														<div class="text-center">
															<div class="text-center user pb-0 font-weight-bold">Patrenna</div>
															<span class="text-center user-semi-title">Web Designer</span>
															<div class="dropdown-divider"></div>
														</div>
														<a class="dropdown-item d-flex" href="profile-1.html">
															<svg class="header-icon me-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM7.07 18.28c.43-.9 3.05-1.78 4.93-1.78s4.51.88 4.93 1.78C15.57 19.36 13.86 20 12 20s-3.57-.64-4.93-1.72zm11.29-1.45c-1.43-1.74-4.9-2.33-6.36-2.33s-4.93.59-6.36 2.33C4.62 15.49 4 13.82 4 12c0-4.41 3.59-8 8-8s8 3.59 8 8c0 1.82-.62 3.49-1.64 4.83zM12 6c-1.94 0-3.5 1.56-3.5 3.5S10.06 13 12 13s3.5-1.56 3.5-3.5S13.94 6 12 6zm0 5c-.83 0-1.5-.67-1.5-1.5S11.17 8 12 8s1.5.67 1.5 1.5S12.83 11 12 11z"/></svg>
															<div class="fs-13">Profile</div>
														</a>
														<a class="dropdown-item d-flex" href="search.html">
															<svg class="header-icon me-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19.43 12.98c.04-.32.07-.64.07-.98 0-.34-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.09-.16-.26-.25-.44-.25-.06 0-.12.01-.17.03l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.06-.02-.12-.03-.18-.03-.17 0-.34.09-.43.25l-2 3.46c-.13.22-.07.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98 0 .33.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.09.16.26.25.44.25.06 0 .12-.01.17-.03l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.06.02.12.03.18.03.17 0 .34-.09.43-.25l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65zm-1.98-1.71c.04.31.05.52.05.73 0 .21-.02.43-.05.73l-.14 1.13.89.7 1.08.84-.7 1.21-1.27-.51-1.04-.42-.9.68c-.43.32-.84.56-1.25.73l-1.06.43-.16 1.13-.2 1.35h-1.4l-.19-1.35-.16-1.13-1.06-.43c-.43-.18-.83-.41-1.23-.71l-.91-.7-1.06.43-1.27.51-.7-1.21 1.08-.84.89-.7-.14-1.13c-.03-.31-.05-.54-.05-.74s.02-.43.05-.73l.14-1.13-.89-.7-1.08-.84.7-1.21 1.27.51 1.04.42.9-.68c.43-.32.84-.56 1.25-.73l1.06-.43.16-1.13.2-1.35h1.39l.19 1.35.16 1.13 1.06.43c.43.18.83.41 1.23.71l.91.7 1.06-.43 1.27-.51.7 1.21-1.07.85-.89.7.14 1.13zM12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 6c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/></svg>
															<div class="fs-13">Settings</div>
														</a>
														<a class="dropdown-item d-flex" href="chat.html">
															<svg class="header-icon me-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M4 4h16v12H5.17L4 17.17V4m0-2c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2H4zm2 10h12v2H6v-2zm0-3h12v2H6V9zm0-3h12v2H6V6z"/></svg>
															<div class="fs-13">Messages</div>
														</a>
														<a class="dropdown-item d-flex" href="login-1.html">
															<svg class="header-icon me-2" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24" viewBox="0 0 24 24" width="24"><g><rect fill="none" height="24" width="24"/></g><g><path d="M11,7L9.6,8.4l2.6,2.6H2v2h10.2l-2.6,2.6L11,17l5-5L11,7z M20,19h-8v2h8c1.1,0,2-0.9,2-2V5c0-1.1-0.9-2-2-2h-8v2h8V19z"/></g></svg>
															<div class="fs-13">Sign Out</div>
														</a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--/app header-->

						<!--Page header-->
						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0 text-primary">Empty</h4>
							</div>
							<div class="page-rightheader">
								<div class="btn-list">
									<button class="btn btn-outline-primary"><i class="fe fe-download me-2"></i>
										Import</button>
									<a href="javascript:void(0);"  class="btn btn-primary btn-pill" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fa fa-calendar me-2 fs-14"></i> Search By Date</a>
									<div class="dropdown-menu border-0">
											<a class="dropdown-item" href="javascript:void(0);">Today</a>
											<a class="dropdown-item" href="javascript:void(0);">Yesterday</a>
											<a class="dropdown-item active" href="javascript:void(0);">Last 7 days</a>
											<a class="dropdown-item" href="javascript:void(0);">Last 30 days</a>
											<a class="dropdown-item" href="javascript:void(0);">Last Month</a>
											<a class="dropdown-item" href="javascript:void(0);">Last 6 months</a>
											<a class="dropdown-item" href="javascript:void(0);">Last year</a>
									</div>
								</div>
							</div>
						</div>
						<!--End Page header-->

						<!-- Row -->
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="card-body">
										Something text type here.....
									</div>
								</div>
							</div>
						</div>
						<!-- End Row -->

					</div>
				</div><!-- right app-content-->
			</div>

			<!--Footer-->
			<footer class="footer">
				<div class="container">
					<div class="row align-items-center flex-row-reverse">
						<div class="col-md-12 col-sm-12 text-center">
							Copyright © 2021 <a href="javascript:void(0);">azea</a>. Designed with <span class="fa fa-heart text-danger"></span> by <a href="javascript:void(0);"> Spruko </a> All rights reserved
						</div>
					</div>
				</div>
			</footer>
			<!-- End Footer-->

		</div>
		<!-- End Page -->

		<!-- Back to top -->
		<a href="#top" id="back-to-top"><i class="fe fe-chevron-up"></i></a>

		<!-- Jquery js-->
		<script src="/assets/js/jquery.min.js"></script>

		<!-- Bootstrap5 js-->
		<script src="/assets/plugins/bootstrap/popper.min.js"></script>
		<script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>

		<!--Othercharts js-->
		<script src="/assets/plugins/othercharts/jquery.sparkline.min.js"></script>

		<!-- Circle-progress js-->
		<script src="/assets/js/circle-progress.min.js"></script>

		<!-- Jquery-rating js-->
		<script src="/assets/plugins/rating/jquery.rating-stars.js"></script>

		<!--Sidemenu js-->
		<script src="/assets/plugins/sidemenu/sidemenu.js"></script>

		<!-- P-scroll js-->
		<script src="/assets/plugins/p-scrollbar/p-scrollbar.js"></script>
		<script src="/assets/plugins/p-scrollbar/p-scroll1.js"></script>
		<script src="/assets/plugins/p-scrollbar/p-scroll.js"></script>

		<!-- Custom js-->
		<script src="/assets/js/custom.js"></script>

	</body>
</html>