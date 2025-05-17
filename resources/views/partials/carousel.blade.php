    @if (is_array($imagens) && count($imagens) > 0)
        <div class="carousel">
            @php $first = true; @endphp
            @foreach ($imagens as $imagem)
                <div class="banner-image" style="display: {{ $first ? 'block' : 'none' }}">
                    <img src="{{ asset($imagem['imagem_path']) }}" alt="{{ $imagem['alt_text'] }}">
                </div>
                @php $first = false; @endphp
            @endforeach
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let currentIndex = 0;
                const images = document.querySelectorAll('.banner-image');

                function showNextImage() {
                    if (images.length === 0) return;
                    images[currentIndex].style.display = 'none';
                    currentIndex = (currentIndex + 1) % images.length;
                    images[currentIndex].style.display = 'block';
                }

                setInterval(showNextImage, 3000);
            });
        </script>
    @else
        <p>Carrossel indisponível.</p>
    @endif
