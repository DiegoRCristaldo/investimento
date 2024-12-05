/*Conteúdo generico do site*/
*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
html{
    min-height: 100vh;
}
body{
    margin: 0 auto;
}
p{
    margin-bottom: 0 !important;
}
form{
    color: #e2e8f0;
    border-radius: 30px;
    box-shadow: 3px 3px 20px #d04205;
    background-color: rgba(34, 5, 251, 0.2);
    padding: 1rem;
}
input {
    margin: 0.5rem !important;
    border-radius: 30px;
    padding: 0.35rem;
    width: 90%;
}
a.text-white:hover{
    color: #d04205 !important;
}
/*Fim do conteúdo generico do site*/

/*Nav*/
.carousel-header {
    position: relative;
    width: 100%;
    overflow: hidden;
}
.navbar{
    justify-content: unset;
}
.fundo-nav{
    background-color: #1e293b;
    padding: 0.5rem;
    font-family: 'Times New Roman', Times, serif, Verdana, Geneva, Tahoma, sans-serif;
}
.navbar-toggler{
    color: transparent !important;
}
span.navbar-toggler-icon{
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%281515, 1515, 1515, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
}
span.navbar-toggler-icon:hover{
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%281300, 42, 05, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
}
.text-white:hover{
    color: #d04205;
}
.titulo{
    color: #d04205;
    -webkit-text-stroke: 0.5px white;
    font-size: 1.6rem;
}
.titulo:hover{
    color: white;
    -webkit-text-stroke: 0.5px #d04205;
}
.investimentos{
    color: #FFFFFF;
    font-size: 1.4rem;
}
.nova-conta, .acesse{
    font-size: 1rem;
    background-color: #d04205;
    padding: 0.5rem;
    border-radius: 20px;
    box-shadow: 3px 3px 3px #c5b8b3;
    color: #ffffff;
    cursor: pointer;
    margin: auto 0;
}
.nova-conta:hover{
    color: #d04205 !important;
    background-color: #ffffff;
    box-shadow: 3px 3px 20px #d04205;
}
a{
    text-decoration: none !important;
    align-items: center;
}
.acesse{
    margin: 0.5rem !important;
    border-radius: 30px;
    padding: 0.35rem 1.5rem;
    width: 90%;
    background-color: #ffffff;
    box-shadow: none;
    border: 0.01px solid rgba(34, 5, 251, 0.35);
    color: #d04205;
}
.acesse:hover{
    background-color: rgba(34, 5, 251, 0.35);
    box-shadow: 3px 3px 20px #0582d0;
}
.laranja, .nav-item:hover{
    color: #d04205;
}
.laranja:hover{
    color: #ffffff;
}
/*Fim do nav*/

/*Início do Carrosseul de indices da bolsa*/
.tag-list{
    width: 100%;
    height: auto;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    position: relative;
    overflow: hidden;
}
.inner{
    margin: 0 auto;
    display: flex;
    gap: 1.5rem;
    animation: loop 30s linear infinite;
}
.fade {
    position: absolute;
    top: 0;
    bottom: 0;
    width: 50px; 
    pointer-events: none;
}

.fade-left {
    left: 0;
    background: linear-gradient(to right, #1e293b, transparent);
}

.fade-right {
    right: 0;
    background: linear-gradient(to left, #1e293b, transparent);
}
.tag{
    display: flex;
    align-items: center;
    gap: 0 0.2rem;
    color: #e2e8f0;
    font-size: 1rem;
    background-color: #11151a;
    padding: 0.3rem 0.8rem;
    border-radius: 0.4rem;
    box-shadow: 0 0.1rem 0.2rem #00000033,
                0 0.1rem 0.5rem #0000004d,
                0 0.2rem 1.5rem #00000066;
}
.variacao, .valor{
    padding: 0.8rem;
}
.variacaoPositiva{
    color: #10e094;
}
.variacaoNegativa{
    color: #FF0000;
}
.variacaoNeutra{
    color: #FFFFFF;
}
@keyframes loop {
    0%{
        transform: translateX(100%);
    }
    100%{
        transform: translateX(-600%);
    }
}
/*Fim do Carrosseul de indices da bolsa*/

/*Texto principal da tela de Login*/
.fundo{
    background-image: <?=$view?>;
    background-repeat: no-repeat;
    background-size: cover;
}
.texto-principal, .tela-principal{
    margin: 4%;
    padding: 1rem;
    color: #e2e8f0;
    border: 1px solid #e2e8f0;
    border-radius: 40px;
    background-color: rgba(34, 5, 251, 0.35);
}
.sub-titulo{
    font-size: 1.6rem;
    color: rgb(58, 57, 57);
    -webkit-text-stroke: 0.5px white;
    margin: 1rem 0;
}
@media (min-width: 425px) {
    .texto-principal, .tela-principal{
        margin: 4% 12%;
    }
}
@media (min-width: 575px){
    .inner{
        animation: loop 40s linear infinite;
    }
    .texto-principal, .tela-principal{
        margin: 4% 20%;
    }
}
@media (min-width: 768px){
    .navbar-toggler{
        display: none;
    }
    .collapse:not(.show) {
        display: flex;
    }
    .navbar-collapse{
        flex-basis: auto;
        flex-grow: unset;
        order: 2;
    }
    .navbar-nav{
        flex-direction: row;
    }
    .mx-1 {
        margin-right: 1rem !important;
        margin-left: 1rem !important;
    }
    .inner {
        animation: loop 50s linear infinite;
    }
    .texto-principal, .tela-principal{
        margin: 4% 28%;
    }
}
@media (min-width: 1024px){
    .texto-principal{
        margin: 4% 12%;
        width: auto;
    }
    .flex-wrap{
        flex-wrap: nowrap !important;
    }
    .tela-principal{
        margin: 8% 32%;
    }
    .inner {
        animation: loop 60s linear infinite;
    }
    .mx-1 {
        margin-right: 2rem !important;
        margin-left: 2rem !important;
    }
}
@media (min-width: 1440px) {
    .texto-principal {
        margin: 4% 24%;
    }
}