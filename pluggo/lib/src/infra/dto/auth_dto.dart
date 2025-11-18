import 'dart:convert';

import '../../domain/entity/auth.dart';

class AuthDto {
  static fromJson(Map<String, dynamic> json) {
    return Auth(
      accessToken: json['accessToken']?? '',   // Corrigido de 'token' para 'accessToken'
      refreshToken: json['refreshToken']?? '',
      expiresIn: json['expiresIn']?? 0,       // Adicionado o campo expiresIn
      type: json['type']?? '',
      name: json['name']?? '',         // Adicionado o campo userType
      );
  }



  static encodeAuth(AuthRequest authRequest) {
    return base64Encode(utf8.encode('${authRequest.email}:${authRequest.password}'));
  }

}