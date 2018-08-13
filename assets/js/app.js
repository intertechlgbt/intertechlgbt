
document.addEventListener('DOMContentLoaded', function() {

	particlesJS('particles', {
		'particles': {
			'number': {
				'value': 50,
				'density': {'enable': true, 'value_area': 800}
			},
			'color': {'value': '#ffffff'},
			'shape': {'type': 'circle'},
			'opacity': {
				'value': 0.5,
				'random': false,
				'anim': {
					'enable': false,
					'speed': 1,
					'opacity_min': 0.1,
					'sync': false
				}
			},
			'size': {
				'value': 3,
				'random': true,
				'anim': {
					'enable': false,
					'speed': 20,
					'size_min': 0.1,
					'sync': false
				}
			},
			'line_linked': {
				'enable': true,
				'distance': 150,
				'color': '#ffffff',
				'opacity': 0.4,
				'width': 1
			},
			'move': {
				'enable': true,
				'speed': 3,
				'direction': 'none',
				'random': true,
				'straight': false,
				'out_mode': 'out',
				'bounce': false,
				'attract': {
					'enable': false,
					'rotateX': 600,
					'rotateY': 1200
				}
			},
			nb: 80
		},
		'interactivity': {
			'detect_on': 'window',
			'events': {
				'onhover': {
					'enable': true,
					'mode': 'bubble'
				},
				'onclick': {
					'enable': true,
					'mode': 'push'
				},
				'resize': true
			},
			'modes': {
				'bubble': {
					'distance': 400,
					'size': 4,
					'duration': 2,
					'speed': 3
				}
			},
			'retina_detect': true
		}
	});



	document.querySelectorAll('.btn-particles').forEach(function(btn) {
		var particles = new Particles(btn, {
			color: '#fff', // '#{{ site.data.template.color.secondary }}',
			// complete: function() {}
		});

		btn.addEventListener('click', function(event) {
			event.stopPropagation();

			particles.disintegrate();

			return false;

		}, true);
	});



	document.querySelectorAll('.count-up').forEach(function(el) {
		var start = ~~el.getAttribute('data-start');
		var end = ~~el.getAttribute('data-end');

		var animation = new CountUp(el, start, end, 0, 2, {
			useEasing: true,
			useGrouping: true, 
			separator: ',', 
			decimal: '.'
		});

		animation.start();
	});



	document.querySelectorAll('.page__header .scroll').forEach(function(el) {
		var href = el.getAttribute('href'),
			dst = document.getElementsByClassName(href.slice(1))[0];

		el.addEventListener('click', function(event) {
			event.preventDefault();

			dst.scrollIntoView({
				block: 'start',
				behavior: 'smooth'
			});

		}, true);
	});



	// End DOMContentLoaded
}, false);
