/*
import 'package:brands_card/src/domain/entity/purchase.dart';
import 'package:brands_card/src/domain/service/cards_services.dart';
import 'package:brands_card/src/domain/service/home_services.dart';
import 'package:brands_card/src/domain/service/notifications_services.dart';
import 'package:brands_card/src/domain/service/user_EC_services.dart';
import 'package:brands_card/src/domain/service/user_services.dart';
import 'package:brands_card/src/domain/service/withdrawals_services.dart';
import 'package:brands_card/src/infra/dto/fee_dto.dart';
import 'package:brands_card/src/infra/dto/invoice_dto.dart';
import 'package:brands_card/src/infra/dto/notification_dto.dart';
import 'package:brands_card/src/presentation/provider/ec_provider.dart';

 */
import 'package:flutter/cupertino.dart';
import 'package:path/path.dart';
import 'package:provider/provider.dart';

import '../../../main.dart';
/*
import '../../domain/entity/transactions.dart';
import '../../domain/service/auth_services.dart';
import '../../domain/service/forms_services.dart';
import '../../domain/service/local_authentication_services.dart';
import 'package:brands_card/src/domain/service/otp_service/otp_service_abstract.dart';
import 'package:brands_card/src/domain/service/otp_service/otp_service_email.dart';
import 'package:brands_card/src/domain/service/otp_service/otp_service_phone.dart';
//import 'package:app/src/domain/service/transaction_services.dart';
import '../../infra/factory/repository_factory.dart';
import '../../presentation/provider/card_list_provider.dart';
import '../dto/withdrawals_history_dto.dart';

 */

class ServiceFactory {
  /*
  AuthService? authService;
  HomeService? homeService;
  UserECService? userECService;
  UserService? userService;
  FormsService? formsService;
  CardsService? cardsService;
  WithdrawalsServices? withdrawalsServices;
  NotificationService? notificationService;
  OtpServiceAbstract? otpService;
  LocalAuthenticationService? localAuthenticationService;
  //TransactionService? transactionService;



  AuthService getAuthService() {
    if (authService != null) return authService!;
    authService = AuthService(getIt<RepositoryFactory>().getAuthRepository(), getIt<RepositoryFactory>().getSecureStorageRepository());
    return authService!;
  }

  HomeService getHomeService() {
    if (homeService != null) return homeService!;
    homeService = HomeService(getIt<RepositoryFactory>().getDinamicRepository(), getIt<RepositoryFactory>().getSecureStorageRepository());
    return homeService!;
  }

  FormsService getFormsService() {
    if (formsService != null) return formsService!;
    formsService = FormsService(getIt<RepositoryFactory>().getDinamicRepository(), getIt<RepositoryFactory>().getSecureStorageRepository());
    return formsService!;
  }

  CardsService getCardsService(BuildContext context) {
    if (cardsService != null) return cardsService!;
    cardsService = CardsService(
        getIt<RepositoryFactory>().getDinamicRepository(),
        Provider.of<CardProvider>(context, listen: false),
        Provider.of<PurchaseProvider>(context, listen: false),
        Provider.of<TransactionProvider>(context, listen: false),
        Provider.of<InvoicesProvider>(context, listen: false)
    );
    return cardsService!;
  }

  WithdrawalsServices getWithdrawalsService(BuildContext context) {
    if (withdrawalsServices != null) return withdrawalsServices!;
    withdrawalsServices = WithdrawalsServices(
      getIt<RepositoryFactory>().getDinamicRepository(),
      Provider.of<WithdrawalHistoryProvider>(context, listen: false),
    );
    return withdrawalsServices!;
  }

  UserECService getECService(BuildContext context) {
    if (userECService != null) return userECService!;
    userECService = UserECService(
        getIt<RepositoryFactory>().getDinamicRepository(),
        getIt<RepositoryFactory>().getSecureStorageRepository(),
        Provider.of<ECProvider>(context, listen: false),
        Provider.of<FeeProvider>(context, listen: false)
    );
    return userECService!;
  }

  UserService getUserService(BuildContext context) {
    if (userService != null) return userService!;
    userService = UserService(
        getIt<RepositoryFactory>().getDinamicRepository(),
        getIt<RepositoryFactory>().getSecureStorageRepository()
    );
    return userService!;
  }

  NotificationService getNotificationService(BuildContext context) {
    if (notificationService != null) return notificationService!;
    notificationService = NotificationService(
        getIt<RepositoryFactory>().getDinamicRepository(),
        Provider.of<NotifProvider>(context, listen: false)
    );
    return notificationService!;
  }

  LocalAuthenticationService getLocalAuthenticationService() {
    if (localAuthenticationService != null) return localAuthenticationService!;
    localAuthenticationService = LocalAuthenticationService();
    return localAuthenticationService!;
  }
/*
  TransactionService getTransactionService(bool hasBio) {
    if (transactionService != null) return transactionService!;
    transactionService = TransactionService(hasBio: hasBio, auth: getLocalAuthenticationService());
    return transactionService!;
  }
*/
  OtpServiceAbstract getOtpService() {
    if (otpService == null) {
      otpService = OtpServiceEmail(getIt<RepositoryFactory>().getRegisterRepository());
      final otpServicePhone = OtpServicePhone(getIt<RepositoryFactory>().getRegisterRepository());
      otpService?.setNext(otpServicePhone);
    }
    return otpService!;
  }


   */

}