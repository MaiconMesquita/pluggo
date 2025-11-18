//import 'package:app/src/domain/entity/pix_key.dart';
//import 'package:app/src/domain/enum/transaction_service.dart';
//import 'package:app/src/domain/enum/transfer_type.dart';
import 'package:flutter/material.dart';

import '../src/presentation/widgets/pop_up/pop_up_confirm_representative.dart';
import '../src/presentation/widgets/pop_up/token_popUp.dart';
import '../src/config/routes/app_routes.dart';
import '../src/presentation/widgets/pop_up/confirmation_popup.dart';
import '../src/presentation/widgets/pop_up/popUp_config.dart';
import '../src/presentation/widgets/pop_up/pop_up_confirmation.dart';
import '../src/presentation/widgets/pop_up/pop_up_notification.dart';

class PopUp {
  final String title;
  final String description;

  const PopUp({required this.title, required this.description});

  static PopUp withDescription(String title, String dynamicDescription) {
    return PopUp(
      title: title,
      description: dynamicDescription,
    );
  }
}

class AlertMessages {
  static const politicallyExposedPersonError = PopUp(
    title: 'Conta Politicamente Exposta',
    description: 'Não é possível abrir uma conta como pessoa politicamente exposta. Entre em contato com o suporte para mais informações.',
  );

  static const authenticationPhoneError = PopUp(
    title: 'Falha de Autenticação',
    description: 'Falha na autenticação via celular. Verifique sua senha e tente de novo.',
  );

  static const unsupportedDevice = PopUp(
    title: 'Dispositivo Não Suportado',
    description: 'Seu dispositivo não suporta esta funcionalidade.',
  );

  static const passwordMismatch = PopUp(
    title: 'Senhas Divergentes',
    description: 'As senhas inseridas não são iguais.',
  );

  static const maxAttemptsReached = PopUp(
    title: 'Tentativas Esgotadas',
    description: 'Você atingiu o limite de tentativas. Solicite novamente o código.',
  );

  static const maxLoginAttemptsReached = PopUp(
    title: 'Tentativas Esgotadas',
    description: 'Você atingiu o limite de tentativas. Sua conta foi bloqueada, para desbloquear entre em contato com o suporte.',
  );

  static const scoreNotApproved = PopUp(
    title: 'Ops, algo deu errado',
    description: 'Infelizmente neste momento não conseguimos aprova-lo como cliente ou estabelecimento na Brandscard. Dica: faca seu cadastro novamente em 06 meses para tentarmos aprova-lo. Agradecemos seu interesse e esperamos em breve estarmos juntos. Brandscard – muito além do limite.',
  );

  static const emailCodeError = PopUp(
    title: 'Falha no Envio do Código',
    description: 'Houve um problema ao enviar o código para o e-mail. Tente novamente.',
  );

  static const phoneCodeError = PopUp(
    title: 'Falha no Envio do Código',
    description: 'Houve um problema ao enviar o código para o celular. Tente novamente.',
  );

  static const genericError = PopUp(
    title: 'Ops, algo deu errado',
    description: 'Ocorreu um erro inesperado, por favor tente novamente mais tarde.',
  );


  static const codeExpired = PopUp(
    title: 'Ops, código expirado',
    description: 'O código já não é mais válido pois você alcançou o tempo limite.',
  );

  static const wrongCode = PopUp(
    title: 'Ops, código inválido',
    description: 'O código digitado não é válido, por favor verifique o código e tente novamente.',
  );

  static const invalidDocument = PopUp(
    title: 'Ops, CPF ou CNPJ inválido',
    description: 'O documento digitado não é válido, por favor verifique o documento e tente novamente. Lembre-se de que deve ser o documento cadastrado na conta',
  );

  static const idChangedSuccessfuly = PopUp(
    title: 'ID alterado!',
    description: 'Seu novo dispositivo agora está válido para o uso, faça o login normalmente',
  );

  static const genericPasswordChangeError = PopUp(
    title: 'Ops, algo deu errado',
    description: 'Verifique se sua nova senha contém 8 ou mais caracteres e contém pelo menos um número e um caractere especial.',
  );

  static const userNotFound = PopUp(
    title: 'Usuário não encontrado.',
    description: 'Não foi possível encontrar um usuário, por favor verifique os dados fornecidos.',
  );

  static const processing = PopUp(
    title: 'Processando',
    description: 'Sua solicitação está sendo processada. Quando finalizada, você será notificado.',
  );

  static const sessionExpired = PopUp(
    title: 'Sessão Expirada',
    description: 'Sua sessão expirou. Faça login novamente.',
  );

  static const partnerBanks = PopUp(
    title: 'Bancos Parceiros',
    description: 'Trabalhamos com bancos parceiros, por isso, o nome de outra instituição pode aparecer nos seus comprovantes.',
  );

  static const kycAlreadyInProgress = PopUp(
    title: 'Verificação de Identidade em Andamento',
    description: 'A verificação de identidade já foi iniciada ou concluída.',
  );

  static const emptyAddress = PopUp(
    title: 'Endereço Vazio',
    description: 'Há informações faltantes para o compartilhamento. Tente novamente ou entre em contato com o suporte.',
  );

  static const forgotPasswordSended = PopUp(
    title: 'Link de Recuperação Enviado',
    description: 'Um link para recuperação da senha foi enviado para sua caixa de mensagens.',
  );

  static const firstPasswordSMSFail = PopUp(
    title: 'Senha não gerada',
    description: "Sua conta foi criada porém não foi possível gerar um senha, use o recurso 'Esqueci senha' para gerar a senha de primeiro acesso.",
  );

  static const codeGenerationSMSFail = PopUp(
    title: 'Código não gerado',
    description: "Seu código de verificação não foi gerado, por favor tente novamente ou entre em contato com o suporte.",
  );

  static const invalidInformation = PopUp(
    title: 'Ops, algo deu errado',
    description: "A senha ou E-mail inseridos no processo estão inválidos, revise e insira os dados cadastrados na conta novamente.",
  );

  static const codeGenerationFailByInput = PopUp(
    title: 'Código não gerado',
    description: "Algum dos campos como E-mail ou CPF não estão válidos, verifique e tente novamente",
  );

  static const newIDValidationCode = PopUp(
    title: 'Código de validação',
    description: "O código é válido por 3 minutos ou até 3 tentativas.",
  );

  static const splitAlreadySettleUp = PopUp(
    title: 'Split já alterado',
    description: "Para alterar o valor do split ou o fornecedor, por favor entre em contato com o suporte",
  );

  static const invalidCode = PopUp(
    title: 'Código inválido',
    description: "Revise o código enviado e tente novamente.",
  );

  static const newPixKey = PopUp(
    title: 'Nova chave pix',
    description: "Digite seu pix abaixo:",
  );

  static const antecipate = PopUp(
    title: 'Antecipação',
    description: "Digite o valor a ser antecipado abaixo:",
  );

  static const errorAntecipate = PopUp(
    title: 'Falha na solicitação',
    description: "Houve uma falha na solicitação da antecipação, tente novamente mais tarde",
  );

  static const payInvoice = PopUp(
    title: 'Pix Copia e Cola',
    description: "Digite o valor a ser pago abaixo e copie o pix gerado:",
  );

  static const noPhoneNumber = PopUp(
    title: 'Número indisponivel',
    description: "Digite sum número de celular abaixo para receber o sms:",
  );

  static const codeValidationSMSFail = PopUp(
    title: 'Código inválido',
    description: "O código digitado está incorreto ou expirou, por favor reenvie outro ou tente novamente.",
  );

  static const pixCodeOnGoing = PopUp(
    title: 'Pix em andamento',
    description: "Já existe um código pix em andamento, aguarde o tempo de expiração para gerar um novo.",
  );


  static const disabled = PopUp(
    title: 'Funcionalidade Desativada',
    description: 'Funcionalidade requer ativação. Para ativar entre em contato conosco.',
  );

  static const noFacePhotoTaken = PopUp(
    title: 'Nenhum Rosto detectado',
    description: "Nenhum rosto foi detectado na imagem, se isso for um engano pressione 'confirmar'",
  );

  static const incompleteForms = PopUp(
    title: 'Cadastro não finalizado',
    description: "Detectamos que seu cadastro não foi finalizado, por favor vá para a tela de cadastro em 'criar conta' ou clique em finalizar.",
  );

  static const inviteAlreadyAccept = PopUp(
    title: 'Convite negado!',
    description: "Você já aceitou o convite de outro estabelecimento, não é permitido aceitar mais.",
  );

  static const userNotActive = PopUp(
    title: 'Ops, algo deu errado',
    description: "O usuário não se encontra ativo para receber convites.",
  );



  static const insuficientUserCash = PopUp(
    title: 'Ops, algo deu errado',
    description: "O usuário não tem créditos suficiantes para esta cobrança.",
  );

  static const inviteAlreadyCanceled = PopUp(
    title: 'Convite já cancelado',
    description: "Você já cancelou este convite.",
  );

  static const paymentNotAccept = PopUp(
    title: 'Pagamento negado!',
    description: "Não foi possivel efetuar o pagamento, saldo insuficiente ou já está paga.",
  );

  static const invalidEmail = PopUp(
    title: 'E-mail Inválido',
    description: 'O e-mail digitado não existe. Verifique e tente novamente.',
  );

  static const invalidCPF = PopUp(
    title: 'CPF Inválido',
    description: 'O CPF digitado não é válido. Verifique e tente novamente.',
  );

  static const invalidPassword = PopUp(
    title: 'Senha Inválida',
    description: 'A senha digitada está incorreta. Verifique e tente novamente.',
  );

  static const invalidPasswordType = PopUp(
    title: 'Senha Inválida',
    description: 'A senha deve conter pelo menos 8 caracteres, incluindo uma letra maiúscula, um número e um símbolo.',
  );

  static const rejectedTransaction = PopUp(
    title: 'Transação Negada',
    description: 'A transação foi rejeitada. Verifique seus dados e saldo antes de tentar novamente.',
  );

  static const accountCreationFailed = PopUp(
    title: 'Erro na Criação da Conta',
    description: 'Não foi possível criar a conta. Tente novamente ou entre em contato com o suporte.',
  );

  static const validateFirstOTPFailed = PopUp(
    title: 'Erro na Autenticação',
    description: 'Não foi possível autenticar a conta. Verifique seu CPF e senha, ou entre em contato com o suporte.',
  );

  static const accountCPFAlreadyInUse = PopUp(
    title: 'Erro na Criação da Conta',
    description: 'O CPF informado já está em uso.',
  );

  static const establishmentNotActivated = PopUp(
    title: 'Conta não ativa',
    description: 'Sua conta encontra-se em avaliação para a ativação, agaurde ou entre em contato om o suporte.',
  );

  static const acceptTermFailed = PopUp(
    title: 'Erro ao Aceitar Termos',
    description: 'Ocorreu um problema ao aceitar os termos. Tente novamente.',
  );

  static const noCardID = PopUp(
    title: 'Cartão Não Encontrado',
    description: 'Você ainda não possui um cartão. Seja convidado por um estabelecimento para começar a usar o BrandsCard!',
  );

  static const cardCreated = PopUp(
    title: 'Cartão criado',
    description: 'Parabéns!! Seu cartão foi criado.',
  );

  static const cantGoBack = PopUp(
    title: 'Edição Não Permitida',
    description: 'Para editar Nome, CPF ou RG, finalize seu cadastro e entre em contato com o suporte na tela de finalização de cadastro.',
  );

  static const accountAlreadyVerified = PopUp(
    title: 'Conta Já Verificada',
    description: 'Esta conta já passou pelo processo de verificação.',
  );

  static const accountVerificationFailed = PopUp(
    title: 'Erro na Verificação da Conta',
    description: 'Não foi possível verificar a conta. Tente novamente mais tarde.',
  );

  static const passwordChanged = PopUp(
    title: 'Senha Alterada',
    description: 'Sua senha foi alterada com sucesso!',
  );

  static const accountCreated = PopUp(
    title: 'Conta Criada!',
    description: 'Sua senha chegará via email, olhe a caixa de menssagens e também a lixeira',
  );

  static const newPasswordSent = PopUp(
    title: 'Nova Senha Gerada',
    description: 'Sua nova senha será enviada por SMS em breve.',
  );

  static const addressChanged = PopUp(
    title: 'Endereço Alterado',
    description: 'O endereço foi atualizado com sucesso!',
  );

  static const phoneNumberChanged = PopUp(
    title: 'Número de Telefone Alterado',
    description: 'O número de telefone foi atualizado com sucesso!',
  );

  static PopUp withdrawlConfirmation(String title ,String infos) {
    return PopUp.withDescription(
      title,
      infos,
    );
  }


  static PopUp inviteAccept(String desc) {
    return PopUp.withDescription(
      'Convite',
      desc,
    );
  }

  static PopUp showNotificationMessage(String desc) {
    return PopUp.withDescription(
      'Notificação',
      desc,
    );
  }

  static PopUp customPopUp(String desc,String title) {
    return PopUp.withDescription(
      title,
      desc,
    );
  }

  static PopUp statementDesc(String desc) {
    return PopUp.withDescription(
      'Descrição',
      desc,
    );
  }

  static PopUp invoicePaymet(String desc) {
    return PopUp.withDescription(
      'Pix copia e cola',
      desc,
    );
  }

  static PopUp notFeatureYet(String desc) {
    return PopUp.withDescription(
      'Em breve',
      desc,
    );
  }

  static PopUp inviteDenied(String desc) {
    return PopUp.withDescription(
      'Convite',
      desc,
    );
  }

  static PopUp Payment(String desc) {
    return PopUp.withDescription(
      'Cobrança',
      desc,
    );
  }

  static PopUp PaymentAccept(String desc) {
    return PopUp.withDescription(
      'Sucesso',
      desc,
    );
  }

  static PopUp PaymentDenied(String desc) {
    return PopUp.withDescription(
      'Cancelado',
      desc,
    );
  }

  static PopUp inviteSent(String desc) {
    return PopUp.withDescription(
      'Convite Enviado!',
      desc,
    );
  }

  static PopUp chargeSent(String desc) {
    return PopUp.withDescription(
      'Cobrança gerada!',
      desc,
    );
  }

  static const noPhotoTaken = PopUp(
    title: 'Nenhuma Foto Capturada',
    description: 'Nenhuma foto foi capturada. Abra a câmera e capture uma foto.',
  );

  static const selectLotteryGame = PopUp(
    title: 'Atenção!',
    description: 'Você está prestes a comprar o jogo selecionado. Confirma a continuação?',
  );

  static const logoutConfirmation = PopUp(
    title: 'Confirmação de Saída',
    description: 'Você realmente deseja sair da conta?',
  );

  static const inviteConfirmation = PopUp(
    title: 'Confirmação de Convite',
    description: 'Deseja aceitar o convite enviado?',
  );

  static const receiptNotFound = PopUp(
    title: 'Comprovante Não Encontrado',
    description: 'Comprovante não foi encontrado. Tente novamente mais tarde.',
  );

  //Representative already accepted
  static const representativeAlreadyAccepted = PopUp(
    title: 'Convite Aceito',
    description: 'O convite de sócio já foi aceito anteriormente.',
  );

  static const representativeConfirmed = PopUp(
    title: 'Convite Aceito',
    description: 'Você aceitou o convite para se tornar sócio.',
  );

  static const representativeRejected = PopUp(
    title: 'Convite Rejeitado',
    description: 'Você rejeitou o convite para se tornar sócio.',
  );

  static const cameraPermission = PopUp(
    title: 'Permissão de Câmera Necessária',
    description: 'Por favor, permita o acesso à câmera para continuar.',
  );

  static const saleTerminalRequestSuccess = PopUp(
    title: 'Solicitação Realizada',
    description: 'Sua solicitação de maquininha foi realizada com sucesso. Entraremos em contato em breve.',
  );

  static const pixKeyRegistered = PopUp(
    title: 'Chave PIX Registrada',
    description: 'Sua chave PIX foi registrada com sucesso.',
  );

  static const pixKeyAlreadyRegistered = PopUp(
    title: 'Chave PIX Já Registrada',
    description: 'Esta chave PIX já está registrada em sua conta.',
  );

  static const deletePixKey = PopUp(
    title: 'Confirmação de Remoção',
    description: 'Você está prestes a remover sua chave PIX cadastrada. Deseja continuar?',
  );

  static const pixKeyDeleted = PopUp(
    title: 'Chave PIX Excluída',
    description: 'Sua chave PIX foi excluída com sucesso.',
  );

  static const pixKeyInPortabilityIn = PopUp(
    title: 'Portabilidade de Entrada',
    description: 'Sua chave PIX está em processo de portabilidade de entrada.',
  );

  static const pixKeyInPortabilityOut = PopUp(
    title: 'Portabilidade de Saída',
    description: 'Sua chave PIX está em processo de portabilidade de saída.',
  );

  static const pixKeyPortabilityRejected = PopUp(
    title: 'Portabilidade Rejeitada',
    description: 'A portabilidade da sua chave PIX foi rejeitada.',
  );

  static const pixKeyNotFound = PopUp(
    title: 'Chave PIX Não Encontrada',
    description: 'A chave PIX informada não foi encontrada. Verifique e tente novamente.',
  );

  static const noPixKeyRegisteredQrCode = PopUp(
    title: 'Nenhuma Chave PIX Cadastrada',
    description: 'Para gerar um QR Code, é necessário ter uma chave PIX cadastrada e selecionada.',
  );

  static const pixTransferWillBeMadeWithPixManual = PopUp(
    title: 'Transferência via PIX Manual',
    description: 'A transferência será realizada via PIX manual, pois a chave PIX informada não foi encontrada.',
  );

  static const transferWillBeMadeWithTed = PopUp(
    title: 'Transferência via TED',
    description: 'A transferência será realizada via TED, pois a chave PIX informada não foi encontrada.',
  );

  static const emailPixKeyNotFound = PopUp(
    title: 'Chave PIX Não Encontrada',
    description: 'Não encontramos a chave PIX associada a este e-mail. Tente outro e-mail ou utilize o CPF/CNPJ para iniciar uma nova transação.',
  );

  static const phonePixKeyNotFound = PopUp(
    title: 'Chave PIX Não Encontrada',
    description:
    'Não encontramos a chave PIX associada a este número de celular. Tente outro número ou utilize o CPF/CNPJ para iniciar uma nova transação.',
  );

  static const randomPixKeyNotFound = PopUp(
    title: 'Chave PIX Não Encontrada',
    description: 'Não encontramos a chave PIX aleatória informada. Tente outra chave ou utilize o CPF/CNPJ para iniciar uma nova transação.',
  );

  static const errorGettingBankList = PopUp(
    title: 'Erro ao Buscar Bancos',
    description: 'Ocorreu um erro ao buscar a lista de bancos. Por favor, tente novamente.',
  );

  static const locationServicesDisabled = PopUp(
    title: 'Serviços de Localização Desativados',
    description: 'Os serviços de localização estão desativados. Por favor, ative-os para continuar.',
  );

  static const locationPermissionsDenied = PopUp(
    title: 'Permissões de Localização Negadas',
    description: 'As permissões de localização foram negadas. Por favor, permita o acesso para continuar.',
  );

  static const locationPermissionsPermanentlyDenied = PopUp(
    title: 'Permissões de Localização Negadas Permanentemente',
    description: 'As permissões de localização foram negadas permanentemente. Por favor, altere as configurações do dispositivo e tente novamente.',
  );

  static const pixLimitChanged = PopUp(
    title: 'Limite Alterado',
    description: 'O limite foi alterado com sucesso!',
  );

  //erro ao buscar chaves pix
  static const errorGettingPixKeys = PopUp(
    title: 'Erro ao Buscar Chaves PIX',
    description: 'Ocorreu um erro ao buscar as chaves PIX. Por favor, tente novamente.',
  );

  //erro ao deletar chave pix
  static const errorDeletingPixKey = PopUp(
    title: 'Erro ao Deletar Chave PIX',
    description: 'Ocorreu um erro ao deletar a chave PIX. Por favor, tente novamente.',
  );

  //erro ao criar chave pix
  static const errorCreatingPixKey = PopUp(
    title: 'Erro ao Criar Chave PIX',
    description: 'Ocorreu um erro ao criar a chave PIX. Por favor, tente novamente.',
  );

  //Transfer limit reached
  static const transferLimitReached = PopUp(
    title: 'Limite de Transferência Atingido',
    description: 'Você atingiu o limite de transferência. Tente novamente mais tarde.',
  );

  //Cannot lower limit to this value
  static const cannotLowerLimit = PopUp(
    title: 'Erro ao Alterar Limite',
    description: 'Não é possível diminuir o limite para este valor. Tente novamente.',
  );

  //Cannot raise limit to this value
  static const cannotRaiseLimit = PopUp(
    title: 'Erro ao Alterar Limite',
    description: 'Não é possível aumentar o limite para este valor. Tente novamente.',
  );

  //errorRequestingPaymentPix
  static const errorRequestingPaymentPix = PopUp(
    title: 'Erro ao Solicitar Pagamento PIX',
    description: 'Ocorreu um erro ao solicitar o pagamento PIX. Por favor, tente novamente.',
  );

  //errorPayingPix
  static const errorPayingPix = PopUp(
    title: 'Erro ao Pagar PIX',
    description: 'Ocorreu um erro ao pagar o PIX. Por favor, tente novamente.',
  );

  //errorGeneratingPix
  static const errorGeneratingPix = PopUp(
    title: 'Erro ao Gerar PIX',
    description: 'Ocorreu um erro ao gerar o PIX. Por favor, tente novamente.',
  );

  //errorDecodingPixQrCode
  static const errorDecodingPixQrCode = PopUp(
    title: 'Erro ao Procurar QR Code',
    description: 'Ocorreu um erro ao procurar o QR Code. Por favor, tente novamente.',
  );

  //errorChangingPixLimit
  static const errorGettingNotifications = PopUp(
    title: 'Erro ao carregar notificações',
    description: 'Ocorreu um erro ao tentar atualizar a caixa de notificações.',
  );

  //errorChangingPixLimit
  static const errorChangingPixLimit = PopUp(
    title: 'Erro ao Alterar Limite PIX',
    description: 'Ocorreu um erro ao alterar o limite PIX. Por favor, tente novamente.',
  );

  //errorGettingPixLimits
  static const errorGettingPixLimits = PopUp(
    title: 'Erro ao Buscar Limites PIX',
    description: 'Ocorreu um erro ao buscar os limites PIX. Por favor, tente novamente.',
  );

  //deseja ativar biometria? a biometria será usado para autenticar e transações
  static const activateBiometry = PopUp(
    title: 'Ativar Biometria',
    description: 'Deseja ativar a biometria? A biometria será usada para autenticar e autorizar transações.',
  );

  //error loading acceptance terms
  static const errorLoadingAcceptanceTerms = PopUp(
    title: 'Erro ao Carregar Termos de Aceitação',
    description: 'Ocorreu um erro ao carregar os termos de aceitação. Por favor, tente novamente.',
  );
}

class ShowPopUp {

  static Future<bool?> showNotification2(PopUp? popUp,{bool? isPortrait = true,bool? success}) async {
    popUp ??= AlertMessages.genericError;
    print(success);
    return showDialog<bool>(
      barrierDismissible: true,
      context: appRouter.navigatorKey.currentContext!,
      builder: (BuildContext context) {
        print("notificação popup");
        return NotificationPopUp(
          popUp: popUp!,
          isPortrait: isPortrait!,
          success: success,
        );
      },
    );
  }

  static Future<bool?> showNotification(PopUpConfig popUp,{bool? isPortrait = true}) async {
    return showDialog<bool>(
      barrierDismissible: true,
      context: appRouter.navigatorKey.currentContext!,
      builder: (BuildContext context) {
        print("notificação popup");
        return NotificationPopUp(
          popUp: popUp.popUp?? AlertMessages.genericError,
          isPortrait: isPortrait!,
          success: popUp.success,
        );
      },
    ).then((value) {
      // Verificando o valor retornado quando o pop-up é fechado
      print("O valor retornado do pop-up é: $value");
      return value;
    });
  }

  static Future<bool?> showConfirmationPopUp(
      PopUp? popUp, {
        bool showCloseButton = true,
        bool showConfirmButton = true,
        String confirmText = 'Confirmar',
        String denyText = 'Voltar',
      }) async {
    return showDialog<bool>(
      barrierDismissible: false,
      context: appRouter.navigatorKey.currentContext!,
      builder: (BuildContext context) {
        print("show confirmation popup");
        return ConfirmationPopUpWidget(
          popUp: popUp ?? AlertMessages.genericError,
          confirmButton: showConfirmButton,
          closeButton: showCloseButton,
          confirmText: confirmText,
          closeText: denyText,
        );


      },
    );
  }
/*
  static Future<bool?> showTokenPopUp({
    required String title,
    required String subtitle,
    required String desc,
    required int setTimer,
    Future<void> Function()? confirmFunction,
    void Function()? denyFunction,
    required bool showDenyButton,
    required TextEditingController controller,
    required GlobalKey<FormState> formKey,
    bool? timer,
  }) async {
    return showDialog<bool>(
      barrierDismissible: false,
      context: appRouter.navigatorKey.currentContext!,
      builder: (BuildContext context) {
        return TokenValidationPopUp(
          title: title,
          subtitle: subtitle,
          desc: desc,
          seconds: setTimer,
          confirmFunction: confirmFunction,
          denyFunction: denyFunction,
          showDenyButton: showDenyButton,
          controller: controller,
          formKey: formKey,
          timer: timer,
        );
      },
    );
  }

 */

  static Future<bool?> showConfirmationWithLoadingButtonPopUp({
    PopUp? popUp,
    String? confirmText,
    String? denyText,
    Future<void> Function()? confirmFunction,
    void Function()? denyFunction,
    bool? showCloseButton,
    bool? showConfirmButton,
  }) async {
    popUp ??= AlertMessages.genericError;
    return showDialog<bool>(
      barrierDismissible: false,
      context: appRouter.navigatorKey.currentContext!,
      builder: (BuildContext context) {
        return ConfirmationWithLoadingButtonPopUpWidget(
          popUp: popUp ?? AlertMessages.genericError,
          confirmText: confirmText ?? 'Confirmar',
          denyText: denyText ?? 'Voltar',
          confirmFunction: confirmFunction,
          denyFunction: denyFunction,
          showCloseButton: showCloseButton ?? true,
          showConfirmButton: showConfirmButton ?? true,
        );
      },
    );
  }

  static Future<bool?> showConfirmRepresentativePopUp({required String id, String? title, String? socialName}) async {
    return showDialog<bool>(
      barrierDismissible: false,
      context: appRouter.navigatorKey.currentContext!,
      builder: (BuildContext context) {
        print("SHOWCONFIRM popup");
        return ConfirmRepresentativePopUpWidget(
          id: id,
          title: title,
          socialName: socialName,
        );
      },
    );
  }
}