<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <title>Nzassa Girl</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <style>
      /* Animation fadeInUp */
      @keyframes fadeInUp {
        from {
          opacity: 0;
          transform: translate3d(0, 20px, 0);
        }
        to {
          opacity: 1;
          transform: translate3d(0, 0, 0);
        }
      }
      .animate-fadeInUp {
        animation-name: fadeInUp;
        animation-timing-function: ease-out;
        animation-fill-mode: forwards;
      }
    </style>
  </head>
  <body class="text-gray-900 bg-gradient-to-b from-pink-50 via-purple-50 to-white">

    <!-- Hero Section -->
      <section class="relative w-full h-[90vh] overflow-hidden">
  <div id="carousel" class="relative w-full h-full">
    <!-- Slide 1 -->
    <div
      class="carousel-slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-100"
      style="background-image: url('https://images.pexels.com/photos/19986548/pexels-photo-19986548/free-photo-of-etre-assis-table-portrait-garcons.jpeg?auto=compress&cs=tinysrgb&w=1600');"
    >
      <div
        class="absolute bottom-0 left-0 right-0 h-1/3 bg-gradient-to-t from-purple-900/90 to-transparent z-10"
      ></div>

      <div
        class="absolute bottom-10 left-1/2 transform -translate-x-1/2 pb-8 px-4 text-center text-pink-100 z-20 transition duration-700 translate-y-10 opacity-0 animate-fadeInUp"
        style="animation-fill-mode: forwards; animation-delay: 0.3s; animation-duration: 1s;"
      >
        <p class="text-xl max-w-3xl mx-auto drop-shadow-md">
          Écoute ton corps, il sait souvent ce dont tu as besoin. Prends le temps de l’écouter attentivement, car il te guide vers ce qui est essentiel pour ton bien-être physique et mental.
        </p>
      </div>
    </div>

    <!-- Slide 2 -->
    <div
      class="carousel-slide absolute inset-0 bg-cover bg-center opacity-0 transition-opacity duration-1000 hidden"
      style="background-image: url('https://images.pexels.com/photos/20333030/pexels-photo-20333030/free-photo-of-amis-heureux-joyeux-content.jpeg?auto=compress&cs=tinysrgb&w=1600');"
    >
      <div
        class="absolute bottom-0 left-0 right-0 h-1/3 bg-gradient-to-t from-purple-900/90 to-transparent z-10"
      ></div>

      <div
        class="absolute bottom-10 left-1/2 transform -translate-x-1/2 pb-8 px-4 text-center text-pink-100 z-20 transition duration-700 translate-y-10 opacity-0 animate-fadeInUp"
        style="animation-fill-mode: forwards; animation-delay: 0.3s; animation-duration: 1s;"
      >
        <p class="text-xl max-w-3xl mx-auto drop-shadow-md">
          Prends soin de toi chaque jour, même pour 5 minutes. Ce petit moment dédié à toi-même peut faire toute la différence dans ta journée et renforcer ta sérénité intérieure.
        </p>
      </div>
    </div>

    <!-- Slide 3 -->
    <div
      class="carousel-slide absolute inset-0 bg-cover bg-center opacity-0 transition-opacity duration-1000 hidden"
      style="background-image: url('https://images.pexels.com/photos/3213283/pexels-photo-3213283.jpeg?auto=compress&cs=tinysrgb&w=1600');"
    >
      <div
        class="absolute bottom-0 left-0 right-0 h-1/3 bg-gradient-to-t from-purple-900/90 to-transparent z-10"
      ></div>

      <div
        class="absolute bottom-10 left-1/2 transform -translate-x-1/2 pb-8 px-4 text-center text-pink-100 z-20 transition duration-700 translate-y-10 opacity-0 animate-fadeInUp"
        style="animation-fill-mode: forwards; animation-delay: 0.3s; animation-duration: 1s;"
      >
        <p class="text-xl max-w-3xl mx-auto drop-shadow-md">
          Ton bien-être est une priorité, pas un luxe. Investir en toi-même chaque jour, c’est t’offrir les clés d’une vie plus épanouie et harmonieuse.
        </p>
      </div>
    </div>
  </div>

  <!-- Navigation -->
  <button
    onclick="prevSlide()"
    class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-purple-700 text-pink-100 px-4 py-2 rounded-full shadow-lg hover:bg-pink-600 transition"
    aria-label="Slide précédente"
  >
    ←
  </button>
  <button
    onclick="nextSlide()"
    class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-purple-700 text-pink-100 px-4 py-2 rounded-full shadow-lg hover:bg-pink-600 transition"
    aria-label="Slide suivante"
  >
    →
  </button>
</section>

    <!-- Fonctionnalités -->
    <section class="py-16 px-6 bg-pink-50">
      <h2 class="text-3xl font-extrabold text-center mb-12 text-purple-700 drop-shadow-md">
        Fonctionnalités clés
      </h2>
      <div class="grid md:grid-cols-2 gap-10 max-w-5xl mx-auto">
        <div class="p-8 bg-white rounded-2xl shadow-xl border-2 border-purple-300 hover:border-pink-400 transition">
          <h3 class="text-xl font-bold mb-3 text-pink-600">🌀 Infos menstruelles</h3>
          <p class="text-purple-800">Un calendrier intuitif pour suivre ton cycle et mieux comprendre ton corps.</p>
        </div>
        <div class="p-8 bg-white rounded-2xl shadow-xl border-2 border-purple-300 hover:border-pink-400 transition">
          <h3 class="text-xl font-bold mb-3 text-pink-600">🪷 Journal intime</h3>
          <p class="text-purple-800">Exprime tes émotions et consigne les moments importants de ta vie.</p>
        </div>
        <div class="p-8 bg-white rounded-2xl shadow-xl border-2 border-purple-300 hover:border-pink-400 transition">
          <h3 class="text-xl font-bold mb-3 text-pink-600">💡 Astuces santé</h3>
          <p class="text-purple-800">Des conseils pratiques sur l’hygiène intime, la nutrition, et le bien-être.</p>
        </div>
        <div class="p-8 bg-white rounded-2xl shadow-xl border-2 border-purple-300 hover:border-pink-400 transition">
          <h3 class="text-xl font-bold mb-3 text-pink-600">📅 Calendrier personnel</h3>
          <p class="text-purple-800">Planifie facilement tes journées, rappels de règles ou RDV médicaux.</p>
        </div>
      </div>
    </section>

    <!-- Témoignages -->
  <section
  class="py-20 px-6 bg-gradient-to-r from-pink-100 via-purple-100 to-indigo-100"
>
  <h2
    class="text-3xl font-extrabold mb-12 text-center text-purple-800 drop-shadow-md"
  >
    Elles en parlent
  </h2>
  <div
    class="max-w-4xl mx-auto grid md:grid-cols-2 gap-12"
  >
    <blockquote
      class="bg-white/90 backdrop-blur-md rounded-xl p-10 italic shadow-lg hover:shadow-xl transition-shadow duration-300"
    >
      <p class="mb-6 text-purple-900 text-lg leading-relaxed">
        "Grâce à Nzassa Girl, je comprends mieux mon cycle et je me sens accompagnée."
      </p>
      <footer class="text-sm font-semibold text-purple-700">— Aïcha, 17 ans</footer>
    </blockquote>
    <blockquote
      class="bg-white/90 backdrop-blur-md rounded-xl p-10 italic shadow-lg hover:shadow-xl transition-shadow duration-300"
    >
      <p class="mb-6 text-purple-900 text-lg leading-relaxed">
        "L’app m’aide à noter mes humeurs, mes douleurs, mes pensées. C’est mon journal secret !"
      </p>
      <footer class="text-sm font-semibold text-purple-700">— Diane, 16 ans</footer>
    </blockquote>
  </div>
</section>

    <!-- CTA final -->
    <section class="bg-gradient-to-r from-pink-400 via-purple-600 to-purple-800 py-20 text-center text-white">
      <h2 class="text-3xl font-extrabold mb-6 drop-shadow-lg">Rejoins la communauté Nzassa Girl !</h2>
      <p class="mb-8 text-lg drop-shadow-md">
        Télécharge l’app dès aujourd’hui et commence ton voyage vers le bien-être féminin.
      </p>
      <div class="flex justify-center gap-6">
        <a
          href="#"
          class="bg-pink-600 hover:bg-pink-700 transition px-8 py-4 rounded-full font-semibold shadow-lg"
        >
          Télécharger sur Play Store
        </a>
        <a
          href="#"
          class="bg-pink-600 hover:bg-pink-700 transition px-8 py-4 rounded-full font-semibold shadow-lg"
        >
          Télécharger sur App Store
        </a>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-purple-900 text-pink-200 py-6 text-center text-sm">
      © 2025 Nzassa Girl. Tous droits réservés. | <a href="#" class="underline hover:text-pink-400">Contact</a> | <a href="#" class="underline hover:text-pink-400">Mentions légales</a>
    </footer>

    <script>
      let currentSlide = 0;
      const slides = document.querySelectorAll('.carousel-slide');

      function showSlide(index) {
        slides.forEach((slide, i) => {
          slide.classList.add('hidden');
          slide.classList.remove('opacity-100');
          slide.classList.add('opacity-0');
          if (i === index) {
            slide.classList.remove('hidden');
            setTimeout(() => {
              slide.classList.add('opacity-100');
            }, 50);
          }
        });
      }

      function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
      }

      function prevSlide() {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
      }

      // Auto-slide every 6s
      setInterval(nextSlide, 6000);
    </script>
  </body>
</html>
