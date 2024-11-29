<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inicio</title>
    <script src="https://kit.fontawesome.com/072340084d.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body style="background-color: rgb(255, 255, 255)">


    <div class="container-fluid">

        <div class="row navbar bg-warning align-items-center">
            <!-- Título a la izquierda en pantallas pequeñas, centrado en pantallas grandes -->
            <div class="col-6 col-md-10 d-flex justify-content-start justify-content-md-center">
                <h2 class="mb-3 " >Minimarket Tip Top</h2>
            </div>

            <!-- Enlace "Iniciar sesión" pegado a la derecha -->
            <div class="col-6 col-md-2 d-flex justify-content-end">
                <a href="{{route('login')}}" class="text-dark ">Iniciar sesión</a>
            </div>
        </div>

        <div class="row container mt-4">
            <div class="col">
                <span><h5>Conoce un poco mas de nosotros...</h5></span>
            </div>
        </div>

        <!-- Fila para las tarjetas y el carrusel -->
        <div class="row g-4">
            <!-- Columna izquierda para las tarjetas -->
            <div class="col-md-4">
                <!-- Tarjeta 1 -->
                <div class="card mb-3 ">
                    <div class="card-body " style="border: 2px solid orange;">
                        <h5 class="card-title"><i class="fa-solid fa-store"></i> ¿Quiénes somos?</h5>
                        <p class="card-text">En Minimarket Tip Top, nos dedicamos a satisfacer las necesidades de nuestros consumidores, brindando una experiencia de compra conveniente y cercana.</p>

                    </div>
                </div>

                <!-- Tarjeta 2 -->
                <div class="card mb-3">
                    <div class="card-body" style="border: 2px solid orange;">
                        <h5 class="card-title"><i class="fa-solid fa-cart-shopping"></i> Misión</h5>
                        <p class="card-text">Nuestra misión es proporcionar productos de calidad y un excelente servicio al cliente, satisfaciendo sus necesidades de manera eficiente.</p>

                    </div>
                </div>

                <!-- Tarjeta 3 -->
                <div class="card mb-3" style="border: 2px solid orange;">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa-solid fa-people-roof"></i> Visión</h5>
                        <p class="card-text">Ser el minimarket líder en la región, reconocido por nuestra calidad de productos y atención al cliente.</p>
                    </div>
                </div>

            </div>
            <!-- Columna derecha para el carrusel -->
            <div class="col-md-8">
                <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel" >
                    <!-- Indicadores -->
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>

                    <!-- Carrusel -->
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="/images/portada/bebidas.png" class="d-block w-100" alt="Imagen de bebidas" style="object-fit: cover; height: 427px; filter: brightness(0.8);">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>Lo mejor en bebidas</h5>
                                <p>Aprovecha de nuestros descuentos.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="/images/portada/abarrotes.png" class="d-block w-100" alt="Imagen de abarrotes" style="object-fit: cover; height: 427px; filter: brightness(0.7);">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>Lo mejor en abarrotes</h5>
                                <p>Productos al alcance del bolsillo.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="/images/portada/compra.png" class="d-block w-100" alt="Imagen de compras" style="object-fit: cover; height: 427px; filter: brightness(0.8);">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>Experiencia de compra</h5>
                                <p>El cliente siempre tiene la razón.</p>
                            </div>
                        </div>

                    </div>

                    <!-- Controles -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            </div>
        </div> <!-- Fin de la fila -->

        <div class="container mt-4">
            <div class="row">
                <!-- Imagen 1 -->
                <div class="col-6 col-md-3 mb-4">
                    <div class="d-flex justify-content-center ">
                        <img src="/images/portada/bebida.png" class="rounded-circle" alt="Imagen 1" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                    <p class="text-center">Bebidas</p>
                </div>

                <!-- Imagen 2 -->
                <div class="col-6 col-md-3 mb-4">
                    <div class="d-flex justify-content-center">
                        <img src="/images/portada/lacteo.png" class="rounded-circle" alt="Imagen 2" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                    <p class="text-center"> Lacteos y huevos</p>

                </div>

                <!-- Imagen 3 -->
                <div class="col-6 col-md-3 mb-4">
                    <div class="d-flex justify-content-center">
                        <img src="/images/portada/cuidadoHogar.png" class="rounded-circle" alt="Imagen 3" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                    <p class="text-center">Cuidado del hogar</p>

                </div>

                <!-- Imagen 4 -->
                <div class="col-6 col-md-3 mb-4">
                    <div class="d-flex justify-content-center">
                        <img src="/images/portada/despensa.png" class="rounded-circle" alt="Imagen 4"  style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                    <p class="text-center">Despensa</p>

                </div>
            </div>
        </div>





    </div>
    <footer class="main-footer" style="display: flex; justify-content: center; align-items: center; text-align: center;">
        <strong>© Todos los derechos reservados Minimarket Tip Top S.A.C. Av. Leoncio Prado N°14, Pacasmayo, Perú.</strong>
    </footer>
    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</body>
</html>
