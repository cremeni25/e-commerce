(() => {
  'use strict';

  const toggle = document.querySelector('.menu-toggle');
  const navigation = document.querySelector('.site-header__nav');

  if (!toggle || !navigation) {
    return;
  }

  const closeMenu = () => {
    toggle.setAttribute('aria-expanded', 'false');
    navigation.classList.remove('is-open');
  };

  toggle.addEventListener('click', () => {
    const isOpen = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', String(!isOpen));
    navigation.classList.toggle('is-open', !isOpen);
  });

  navigation.addEventListener('click', (event) => {
    if (event.target instanceof HTMLAnchorElement) {
      closeMenu();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      closeMenu();
    }
  });
})();
