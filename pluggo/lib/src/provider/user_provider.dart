import 'package:pluggo/src/provider/provider.dart';

class UserProvider extends CustomChangeNotifier {
  String id = '';
  String name = '';
  String firstName = '';
  String email = '';
  String googleAccessToken = '';
  String googleRefreshToken = '';
  bool hasBio = false;

  void setUser({
    required String? id,
    required String? name,
    required String? email,
    required String? googleAccessToken,
    required String? googleRefreshToken,
  }) {
    this.id = id?? '';
    this.name = name?? '';
    this.email = email?? '';
    this.googleAccessToken = googleAccessToken?? '';
    this.googleRefreshToken = googleRefreshToken?? '';
    notifyListeners();
  }

  void setAccessToken(String token) {
    googleAccessToken = token;
    notifyListeners();
  }

  void setRefreshToken(String token) {
    googleRefreshToken = token;
    notifyListeners();
  }

  void setEmail(String value) {
    email = value;
    notifyListeners();
  }

  void setname(String? value) {
    name = value!;
    List<String> nameParts = name.split(' ');
    firstName = nameParts.isNotEmpty ? nameParts[0] : '';
    notifyListeners();
  }

  void setId(String value) {
    id = value;
    notifyListeners();
  }

  void setHasBio(bool value) {
    hasBio = value;
    notifyListeners();
  }

  void clearUser() {
    id = '';
    name = '';
    email = '';
    googleAccessToken = '';
    googleRefreshToken = '';
    notifyListeners();
  }
}
