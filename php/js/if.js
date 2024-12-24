  //Deixar as duas condições abaixo sempre no final do arquivo, para evitar erro de referência e prejudicar outras funções
  if(userFirstTime == true && firstAccess == true){
    if($('#ModalSubReg').length > 0){
      $('#ModalSubReg').modal('show');
    }
  } else {
    if(userFirstTime) {
      if($('#modalRegisterConfirmed').length > 0){
        $('#modalRegisterConfirmed').modal('show');
      }
    }
    if(firstAccess) {
      if($('#ModalSub').length > 0){
        $('#ModalSub').modal('show');
      }
    }
  }