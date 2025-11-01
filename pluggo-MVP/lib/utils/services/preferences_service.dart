import 'dart:ffi';

import 'package:shared_preferences/shared_preferences.dart';

class PreferencesService {
  // Singleton pattern
  static final PreferencesService _instance = PreferencesService._internal();
  factory PreferencesService() => _instance;
  PreferencesService._internal();

  SharedPreferences? _prefs;

  Future<void> init() async {
    _prefs = await SharedPreferences.getInstance();
  }

  // Função para verificar se a tela de acesso foi mostrada
  Future<bool> getCheckFirst() async {
    return _prefs?.getBool('checkFirst') ?? false;
  }

  // Função para salvar o estado de tela de acesso
  Future<void> setCheckFirst(bool value) async {
    await _prefs?.setBool('checkFirst', value);
  }

  //para capturar nome
  Future<String> getName() async {
    print("pegou nome" );
    return _prefs?.getString('setName') ?? "erro";
  }

  Future<void> setName(String value) async {
    print("salvou nome" + value);
    await _prefs?.setString('setName', value);
  }

  //capturar biometria
  Future<void> setBiometry(bool value) async {
    print("setou" + value.toString());
    await _prefs?.setBool('setBiometry', value);
  }

  Future<bool> checkBiometry() async {
    print("deu check" + _prefs!.getBool('setBiometry').toString());
    return _prefs?.getBool('setBiometry') ?? false ;
  }
}
