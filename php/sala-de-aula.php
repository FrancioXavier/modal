<style>
.confirm-button {
    background-color: #2E70EB;
    border: none;
    width: 90%;
    padding: 10px 0;
    border-radius: 999px;
    transition: background-color 0.3s ease;
}

.confirm-button-text {
  font-size: 16px;
  color: #fff;
  font-weight: 600;
}

.confirm-button:hover {
    background-color: #13439c;
}

.header-title {
    display: flex;
    justify-content: center;
}

.modal-head {
    padding-bottom: 0;
}

.modal-title {
    color: #101828;
    margin-bottom: 1rem;
    font-weight: 600;
    font-size: 20px;
    text-align: center;
    line-height: 28px;
}

.check-circle {
    width: 48px;
    height: 48px;
    border-radius: 999px;
    background-color: #DCFAE6;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-confirm-container {
    width: 400px;
    border-radius: 16px;
    box-shadow:
    0px 20px 24px -4px rgba(16, 24, 40, 0.08),
    0px 8px 8px -4px rgba(16, 24, 40, 0.03);
}

.icon-container {
    width: 100%;
    display: flex;
    justify-content: center;
}

.check-img {
    width: 24px;
    height: 24px;
}

.content-text {
    width: 100%;
    justify-content: center;
    align-items: center;
    display: flex;
    flex-direction: column;
    padding: 16px;
}

.text-container p{
    font-weight: 400;
    color: #475467;
    width: 100%;
    text-align: center;
    margin: 0;
    line-height: 20px;
}

.text-container {
    width: 90%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.button-div {
    display: flex;
    justify-content: center;
    width: 100%;
}

.screen-container {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

@media only screen and (min-width: 500px) and (max-width: 1023px){
    .modal-confirm-container {
        width: 61.467vw;
    }

    .modal-header {
        padding: 0;
    }

    .text-container p {
        width: 100%;
    }
}

@media only screen and (max-width: 500px) {
    .modal-confirm-container {
        width: 91.467vw;
    }

    .modal-header {
        padding: 0;
    }

    .text-container p {
        width: 100%;
    }

    .button-div {
        padding-top: 8px;
    }
}
</style>

<?php

//O parâmetro 2 informará a chave primária do curso
$id_curso = ($system->getParameter(2) !== false) ? (int) $system->getParameter(2) : null;

//O parâmetro 1 da url informará a view que deverá ser exibida.
$view = ($system->getParameter(1) !== false) ? $system->getParameter(1) : null;

if ($view !== "conteudo-gravado") {
  //Instancio o objeto inscrição que será usado em toda a sala de aula.
  $objInscricao = new Inscricao();

  if (is_null($id_curso)) {
    if ($view !== "tutorial") {
      $view = "lista_inscricoes";
    }
  } else {
    $objInscricao->setId_usuario($sessao->getValor("usuario_id"));
    $objInscricao->setId_curso($id_curso);
    $load = $objInscricao->loadAllId(array('id_usuario' => $sessao->getValor("usuario_id"), 'id_curso' => $id_curso));
  }
}

$obj_curso = new Curso();
$obj_curso->loadSelect($id_curso, 'curso, data_lancamento, investimento');

if(!is_null($obj_curso->data_lancamento) && time() < strtotime($obj_curso->data_lancamento)){
  $view = 'aviso-lancamento';
}

switch ($view) {

  case 'aviso-lancamento':
  include_once 'view/sala_de_aula/aviso-lancamento.php';
  break;

  case 'curso':

  //Instancio os objetos que serão necessários
  $objMaterial = new Material($objInscricao, !$load);
  $objAvaliacao = new Avaliacao($objInscricao, !$load);
  $objCertificado = new Certificado($objInscricao, !$load);

  //mudei aqui pq já caí direto na parte das video aulas
  include_once 'view/sala_de_aula/curso.php';
  break;

  case 'material':

  //Instancio os objetos que serão necessários
  $objMaterial = new Material($objInscricao, !$load);
  $objAvaliacao = new Avaliacao($objInscricao, !$load);
  $objCertificado = new Certificado($objInscricao, !$load);

  include_once 'view/sala_de_aula/material.php';
  break;

  case 'avaliacao':

  //Instancio os objetos que serão necessários
  $objAvaliacao = new Avaliacao($objInscricao, !$load);
  $objCertificado = new Certificado($objInscricao, !$load);

  include_once 'view/sala_de_aula/avaliacao.php';

  break;

  case 'oferta-minicurso':
  include_once 'view/sala_de_aula/oferta-minicurso.php';
  break;

  case 'oferta-certificado':
    $objMaterial = new Material($objInscricao, !$load);
    $objAvaliacao = new Avaliacao($objInscricao, !$load);
    $objCertificado = new Certificado($objInscricao, !$load);
    include_once 'joinCursosInscricoes.php';

    include_once 'view/sala_de_aula/oferta-certificado.php';
    break;

    case "videoaulas":
    include_once 'view/sala_de_aula/videoaulas.php';
    break;

    case "conteudo-gravado":

    //Inicia-se um outro ambiente de sala de aula diferente do que exibe os cursos
    $objPalestra = new Palestra();
    $viewCG = ($system->getParameter(2) !== false) ? $system->getParameter(2) : null;
    switch ($viewCG) {
      case 'material':
      //O parâmetro 3 informará a chave primária da palestra (webinar ou gravada)
      $id_palestra = ($system->getParameter(3) !== false) ? (int) $system->getParameter(3) : null;
      include_once 'view/sala_de_aula/conteudo-gravado/material.php';
      break;
      default :
      echo "<script> window.location.href='" . base_url("aluno/sala-de-aula") . "'; </script>";
      //header("Location: ".  base_url("aluno/sala-de-aula"));
      break;
    }

    break;

    case "palestra-ao-vivo":
    include_once 'view/sala_de_aula/palestra-ao-vivo/index.php';
    break;

    case "tutorial":

    $pageTutorial = ($system->getParameter(2) !== false) ? $system->getParameter(2) : null;

    switch ($pageTutorial) {
      case 'orientacoes-para-webinar-mac-chrome':
      include_once 'view/sala_de_aula/palestra-ao-vivo/tutorial/orientacoes-para-webinar-mac-chrome.php';
      break;
      case 'orientacoes-para-webinar-mac-firefox':
      include_once 'view/sala_de_aula/palestra-ao-vivo/tutorial/orientacoes-para-webinar-mac-firefox.php';
      break;
      case 'orientacoes-para-webinar-mac-safari':
      include_once 'view/sala_de_aula/palestra-ao-vivo/tutorial/orientacoes-para-webinar-mac-safari.php';
      break;
      case 'orientacoes-para-webinar-windows-chrome':
      include_once 'view/sala_de_aula/palestra-ao-vivo/tutorial/orientacoes-para-webinar-windows-chrome.php';
      break;
      case 'orientacoes-para-webinar-windows-firefox':
      include_once 'view/sala_de_aula/palestra-ao-vivo/tutorial/orientacoes-para-webinar-windows-firefox.php';
      break;
      case 'orientacoes-para-webinar-windows-internet-explorer':
      include_once 'view/sala_de_aula/palestra-ao-vivo/tutorial/orientacoes-para-webinar-windows-internet-explorer.php';
      break;
      case 'orientacoes-para-webinar-windows-phone':
      include_once 'view/sala_de_aula/palestra-ao-vivo/tutorial/orientacoes-para-webinar-windows-phone.php';
      break;
      case 'orientacoes-para-webinar-ios':
      include_once 'view/sala_de_aula/palestra-ao-vivo/tutorial/orientacoes-para-webinar-ios.php';
      break;
      case 'orientacoes-para-webinar-android':
      include_once 'view/sala_de_aula/palestra-ao-vivo/tutorial/orientacoes-para-webinar-android.php';
      break;
      case 'como-assistir-a-palestra':
      include_once 'view/sala_de_aula/palestra-ao-vivo/tutorial/como-assistir-a-palestra.php';
      break;
      default :
      include_once 'view/sala_de_aula/palestra-ao-vivo/tutorial/instrucoes.php';
      break;
    }

    break;

    default :
    include_once 'joinCursosInscricoes.php';
    include_once 'view/sala_de_aula/lista_inscricoes.php';
    break;
  }

  if($_SESSION) {
    if($_SESSION['first_time'] == true || $_SESSION['first_access'] == true) {
      if($_SESSION['first_time'] == true && $_SESSION['first_access'] == true) {
        echo "<script> let userFirstTime = true; let firstAccess = true;</script>";
      } else if($_SESSION['first_time'] == true) {
        echo "<script> let userFirstTime = true; let firstAccess = false;</script>";
      } else {
        echo "<script> let userFirstTime = false; let firstAccess = true;</script>";
      }
      $_SESSION['first_time'] = false;
      $_SESSION['first_access'] = false;
    }
  };
  ?>
  <!-- <?php if(((date('H') >= 8 && date('H') < 17 && (date('N') > 0 && date('N') < 6)) || (date('N') == 6 && date('H') >= 8 && date('H') < 12))): ?>
  <a href="https://www.ninpe.com.br/whatsapp" target="_blank" style="position: fixed; bottom: 15px; right: 20px; z-index: 9999999;">
    <img src="<?php echo "https://www.ninpe.com.br/ava/assets/images/icone-whatsapp.png"; ?>" height="50px" alt="Suporte por whatsapp" class="responsive-icon">
  </a>
  <?php endif; ?> -->

  <div id="modalRegisterConfirmed" class="modal fade" role="dialog">
  <div class="screen-container">
    <div class="modal-dialog modal-confirm-container" role="document">
      <div class="modal-content" style="border-radius: 12px;">
        <div class="modal-head" style="border: none">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: 1rem 1rem 0 0">
            <span aria-hidden="true"><img src="https://www.simplesead.com.br/ava/assets/images/confirmacao-evento/x-close.png" style="width: 24px; height: auto;"
                alt="" /></span>
          </button>
          <div class="icon-container">
            <div class="check-circle">
              <img src="https://www.simplesead.com.br/ava/assets/images/confirmacao-evento/check-circle.png" class="check-img" alt="" />
            </div>
          </div>
        </div>
        <div class="modal-body content-text" style="border: none">
          <h3 class="modal-title">Cadastro confirmado!</h3>
          <div class="text-container">
            <p>Tudo pronto! Escolha o curso desejado</p>
            <p>e comece a estudar agora mesmo. 🚀📚</p>
          </div>
        </div>
        <div class="modal-footer button-div" style="border: none;border-radius:12px;margin: 0;">
          <button type="button" class="confirm-button confirm-button-text" onclick="window.location.href='<?php echo base_url('cursos') ?>';" data-dismiss="modal">
              Escolher um curso
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="ModalSub" class="modal fade" role="dialog">
<div class="screen-container">
  <div class="modal-dialog modal-confirm-container" role="document">
    <div class="modal-content" style="border-radius: 12px;">
      <div class="modal-head" style="border: none">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: 1rem 1rem 0 0">
          <span aria-hidden="true"><img src="https://www.simplesead.com.br/ava/assets/images/confirmacao-evento/x-close.png" style="width: 24px; height: auto;"
              alt="" /></span>
        </button>
        <div class="icon-container">
          <div class="check-circle">
            <img src="https://www.simplesead.com.br/ava/assets/images/confirmacao-evento/check-circle.png" class="check-img" alt="" />
          </div>
        </div>
      </div>
      <div class="modal-body content-text" style="border: none">
        <h3 class="modal-title">Inscrição realizada com sucesso!</h3>
        <div class="text-container">
          <p>Parabéns! Agora você tem acesso completo a todos os materiais e conteúdos das aulas. Aproveite e bons
            estudos! 🎓✨</p>
        </div>
      </div>
      <div class="modal-footer button-div" style="border: none;border-radius:12px;margin: 0;">
        <button type="button" class="confirm-button confirm-button-text" data-dismiss="modal" data-toggle="modal" data-target="#modalConvite">
            Acessar material
        </button>
      </div>
    </div>
  </div>
</div>
</div>
<div id="ModalSubReg" class="modal fade" role="dialog">
<div class="screen-container">
  <div class="modal-dialog modal-confirm-container" role="document">
    <div class="modal-content" style="border-radius: 12px;">
      <div class="modal-head" style="border: none">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: 1rem 1rem 0 0">
          <span aria-hidden="true"><img src="https://www.simplesead.com.br/ava/assets/images/confirmacao-evento/x-close.png" style="width: 24px; height: auto;"
              alt="" /></span>
        </button>
        <div class="icon-container">
          <div class="check-circle">
            <img src="https://www.simplesead.com.br/ava/assets/images/confirmacao-evento/check-circle.png" class="check-img" alt="" />
          </div>
        </div>
      </div>
      <div class="modal-body content-text" style="border: none">
        <h3 class="modal-title">Tudo pronto! 🎉</h3>
        <div class="text-container">
          <p style="padding-bottom: 2rem;">Parabéns! Seu cadastro foi realizado com sucesso, e sua inscrição foi
                concluída. Agora você tem acesso
                completo a todos os materiais e conteúdos das aulas.</p>
              <p> Aproveite essa jornada de aprendizado e bons estudos! 🚀📚</p>
        </div>
      </div>
      <div class="modal-footer button-div" style="border: none;border-radius:12px;margin: 0;">
        <button type="button" class="confirm-button confirm-button-text" data-dismiss="modal" data-toggle="modal" data-target="#modalConvite">
            Acessar meu curso
        </button>
      </div>
    </div>
  </div>
</div>
</div>
