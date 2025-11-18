
import 'package:firebase_auth/firebase_auth.dart';
import 'package:flutter/cupertino.dart';
import 'package:pluggo/src/config/routes/app_routes.dart';
import 'package:pluggo/src/domain/entity/device.dart';
import 'package:pluggo/src/infra/dto/product_dto.dart';
import '../../config/routes/app_routes.gr.dart';

import '../../../utils/warning_messages.dart';
import 'package:flutter/services.dart';
import 'package:flutter_native_splash/flutter_native_splash.dart';
import '../../domain/entity/auth.dart';
import '../../domain/service/secure_storage_service.dart';
import '../../infra/dto/auth_dto.dart';
import '../../infra/dto/login_dto.dart';
import '../../infra/repository/api_endpoints.dart';
import '../../infra/repository/dinamic_repository.dart';
import '../../infra/repository/secure_storage_repository.dart';
import '../../presentation/controller/scroll_position_controller.dart';
import '../../presentation/provider/inputs/auth_input.dart';
import '../../presentation/provider/inputs/forms.dart';
import '../../presentation/widgets/button/custom_rounded_loading_button.dart';
import '../../presentation/widgets/pop_up/popUp_config.dart';
import '../provider.dart';
import '../user_provider.dart';
import 'package:google_sign_in/google_sign_in.dart';

class HomeProvider1 extends CustomChangeNotifier {
  final SecureStorageRepository secureStorageRepository;
  final UserProvider userProvider;
  final ProductProvider productProvider;
  final DinamicRepository dinamicRepository;


  HomeProvider1(
      this.userProvider,
      this.secureStorageRepository,
      this.productProvider,
      this.dinamicRepository
      ) {
    _getSecureData();
    FlutterNativeSplash.remove();
  }

  final CustomLoadingButtonController btnController = CustomLoadingButtonController();

  AuthInput authInput = AuthInput();
  SignUpInput signUpInput = SignUpInput();

  bool hasAccount = false;
  bool loginStatus = false;
  bool isLogin = false;
  bool hasLoginBio = false;
  bool hasLoginBioEC = false;
  String login = '';
  bool isLoading = false;
  int step = 1;
  String entity = '';

  final GoogleSignIn googleSignIn = GoogleSignIn(
    scopes: [
      'email',
      'https://www.googleapis.com/auth/calendar', // <-- necessário para criar eventos
    ],
  );
  final FirebaseAuth _auth = FirebaseAuth.instance;

  void setEntity(String value) {
    entity = value;
    notifyListeners();
  }

  Future<void> handleGoogleSignIn() async {
    try {
      print('[Google Sign-In] Iniciando processo de login...');

      final GoogleSignInAccount? googleUser = await googleSignIn.signIn();
      print('[Google Sign-In] Conta selecionada: ${googleUser?.email}');

      if (googleUser == null) {
        print('[Google Sign-In] Usuário cancelou o login.');
        return;
      }

      final GoogleSignInAuthentication googleAuth = await googleUser.authentication;
      print('[Google Sign-In] Token de acesso: ${googleAuth.accessToken}');
      print('[Google Sign-In] Token ID: ${googleAuth.idToken}');

      final OAuthCredential credential = GoogleAuthProvider.credential(
        accessToken: googleAuth.accessToken,
        idToken: googleAuth.idToken,
      );

      final UserCredential userCredential = await _auth.signInWithCredential(credential);
      print('[Google Sign-In] Usuário autenticado: ${userCredential.user?.displayName}, email: ${userCredential.user?.email}');

      //aqui eu faço a chamada de login usando o email só afinal só vai entrar se logar no google
      // e ai o id retornado eu repasso no setUser
      if(googleAuth.accessToken!.isNotEmpty){
        await secureStorageRepository.setItem("accessToken", googleAuth.accessToken!);

        userProvider.setUser(
            id: '',
            name: userCredential.user?.displayName,
            email: userCredential.user?.email,
            googleAccessToken: googleAuth.accessToken,
            googleRefreshToken: userCredential.user?.refreshToken
        );
        userProvider.setname(userCredential.user?.displayName);

        //await _login(userCredential.user?.email);
      }
      return;
    } catch (e, stacktrace) {
      print('[Google Sign-In] Erro durante o login: $e');
      print('[Google Sign-In] Stacktrace: $stacktrace');
      return;
    }
  }

  Future<bool> signing() async {
    final email = authInput.emailController.text;
    final password = authInput.passwordController.text;
    AuthRequest authRequest = AuthRequest(
        email: email, password: password);

      final basicAuth = 'Basic ${AuthDto.encodeAuth(authRequest)}';
      try {
        btnController.start();

        final response = await dinamicRepository.postRequest(
            url: ApiEndpoints.urlLogin,
            body: {
              'entityType': entity
            },
            popups: {
              401: PopUpConfig(
                  popUp: AlertMessages.invalidPassword, success: false),
              406: PopUpConfig(
                  popUp: AlertMessages.invalidEmail, success: false)
            },
            authorization: basicAuth
        );

        final authResponse = AuthDto.fromJson(response?.data);

        await secureStorageRepository.setItem('accessToken', authResponse.accessToken);
        await secureStorageRepository.setItem('refreshToken', authResponse.refreshToken);
        if(response?.statusCode == 201) {
          if(entity == "driver") {
            appRouter.replaceAll([const MainRoute()]);
          } else {
            appRouter.replaceAll([ MainHostRoute()]);
          }
        }
        btnController.stop();
        return true;
      } catch (e, stacktrace) {
        btnController.stop();
        if (e.toString().contains('401')) {
          return false; // ✅ retorno caso seja erro 401
        } else {
          rethrow;
        }
      }
  }

  Future<bool> signup() async {
    final email = signUpInput.emailController.text;
    final name = signUpInput.nameController.text;
    final phone = signUpInput.phoneController.text;

    try {
      btnController.start();
      final response = await dinamicRepository.postRequest(
          url: ApiEndpoints.urlCreateAccount,
          body: {
            "name":name,
            "email":email,
            "phone":phone,
            "entity":entity
          },
          popups: {
            401: PopUpConfig(
                popUp: AlertMessages.invalidPassword, success: false),
            406: PopUpConfig(
                popUp: AlertMessages.invalidEmail, success: false),
            204: PopUpConfig(
                popUp: AlertMessages.accountCreated, success: true)
          },
          authorization: ''
      );

      if(response?.statusCode == 204) {
        btnController.stop();
        appRouter.replaceAll([const FirstAccessRoute()]);
      }

      return true;
    } catch (e, stacktrace) {
      btnController.stop();
      if (e.toString().contains('401')) {
        return false; // ✅ retorno caso seja erro 401
      } else {
        rethrow;
      }
    }
  }

  Future<void> _getSecureData() async {
    await SecureStorageService.init();
  }

  Future<void> handleLoginPassword() async {
    appRouter.replaceAll([const MainRouter()]);
  }

  Future<void> handleLoginCpfAndPassword() async {
    login = authInput.cpfController.text;
    await _authenticateWithPassword();
  }


  Future<void> saveLogin() async {
    await secureStorageRepository.setItem("userPassword", authInput.passwordController.text);
    await secureStorageRepository.setItem("document", authInput.cpfController.text);
  }


  Future<void> _authenticateWithPassword() async {
    btnController.start();

    try {

    } catch (e, stackTrace) {
      btnController.error();
      notifyListeners();

    }
  }

/*
  Future<void> _bioAuthenticate() async {
    btnController.start();
    final accessToken = await secureStorageRepository.getItem('accessToken');
    final password = await secureStorageRepository.getItem("userPassword");
    final cpf = authInput.cpfController.text.isEmpty
        ? userProvider.document
        : Document(authInput.cpfController.text);

    NewAuthRequest authRequest = NewAuthRequest(
        deviceId: Device.unicDeviceId, password: password?? '');

    try {
      await homeService.loginBio(authRequest, cpf.getUnformatted);
      final profile = await homeService.getUserData(accessToken);
      userProvider.setProfile(profile);
      userProvider.setname(profile.userProfile.name);
      if (disposed) return;
      btnController.success();
      appRouter.replaceAll([const MainRouterPage()]);
    } catch (e, stackTrace) {
      btnController.stop();
      appRouter.push(LoginRoute());
      if (disposed) return;
      btnController.stop();
      notifyListeners();
      if (e is ApiException && e.message == AlertMessages.sessionExpired) {
        authInput.passwordController.text = '';
        appRouter.push(LoginRoute());
      } else if (e is! Exception) {
        await handleError(e, stackTrace);
      }
      btnController.reset();
    }
  }


 */

  void chooseLogin() async {
  }

  Future<void> handlAuthenticate() async {
    await secureStorageRepository.getItem("isCompany");
    await _getSecureData();
    if (hasLoginBio) {
     // _bioAuthenticate();
    } else {
      btnController.stop();
    }
  }


  void handleLoginCheck() async {
  }

  Future<void> handleActivateBio(bool isFromLogin) async {
    bool authenticated = false;
    try {
      /*
      authenticated = await homeService.localAutorization();
      if (authenticated == true) {
        userProvider.setHasBio(true);
        await secureStorageRepository.setItem("hasBio", "true");
        notifyListeners();
      } else {
        btnController.stop();
        if(isFromLogin) {
          appRouter.push(
              LoginRoute());
        }
      }
             */


    } on PlatformException {
      ShowPopUp.showNotification2(AlertMessages.authenticationPhoneError);
      return;
    }
  }

  Future<void> handleDesactivateBio() async {
    userProvider.setHasBio(false);
    await secureStorageRepository.setItem("hasBio", "false");
    notifyListeners();
  }

  Future<bool> isBioActive() async {
    String? hasBio = await secureStorageRepository.getItem("hasBio");
    return hasBio == "true";
  }


  void handleHasOtherLoginPage() {
  }

  void handleHasAccountOtherLoginPage() {
  }

  void handleHasECLoginPage() {
  }

  Future<void> progress() async {
    isLoading = true;
    isLoading = false;
  }

  Future<void> goToSignup() async {
    appRouter.push( FormsRoute());
  }

  void goToForgotPasswordRoute() {
  }
}