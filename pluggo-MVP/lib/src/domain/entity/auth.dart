import '../../presentation/provider/inputs/auth_input.dart';

class Auth {
  final String accessToken;
  final String refreshToken;
  final int expiresIn;
  final String type;
  final String name;

  Auth({required this.accessToken, required this.refreshToken, required this.expiresIn, required this.type,required this.name});
}

class AuthRequest {
  final String email;
  final String password;

  AuthRequest({
    required this.email,
    required this.password,
  });

  static AuthRequest fromInputSms(AuthInput authInput) {
    return AuthRequest(
      email: authInput.emailController.text,
      password: authInput.passwordController.text,
    );
  }
}