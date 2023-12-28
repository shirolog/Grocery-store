(() => {

    //ヘッダーナビゲーション設定
    const $navBar = document.querySelector('.header .flex .navbar');
    const $menuBtn = document.querySelector('#menu-btn');
    const $profile = document.querySelector('.header .flex .profile');
    const $userBtn = document.querySelector('#user-btn');


    $menuBtn.addEventListener('click', ()=>{
        $navBar.classList.toggle('active');
        $menuBtn.classList.toggle('fa-times');
        $profile.classList.remove('active');
    })

    $userBtn.addEventListener('click', ()=>{
        $profile.classList.toggle('active');
        $navBar.classList.remove('active');
        $menuBtn.classList.remove('fa-times');
    })


    window.addEventListener('scroll', ()=>{
        $navBar.classList.remove('active');
        $menuBtn.classList.remove('fa-times');
        $profile.classList.remove('active');
    })


})();
