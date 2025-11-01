
class User {
  final String id;
  final String name;
  final String firstName;
  final String email;
  final String hierarchy;
  final String documentMasked;
  final String documentType;
  final String bankCompe;
  final String branch;
  final String accountNumber;
  final String digit;
  final bool? ccbStatus;
  final bool? hasCompany;
  final bool? isCompany;
  final bool? hasPin;
  final bool? changeDevice;
  final bool? mustChangePassword;

  User({
    required this.id,
    required this.name,
    required this.firstName,
    required this.email,
    required this.hierarchy,
    required this.documentMasked,
    required this.documentType,
    required this.bankCompe,
    required this.branch,
    required this.accountNumber,
    required this.digit,
    this.ccbStatus,
    this.hasCompany,
    this.isCompany,
    this.hasPin,
    this.changeDevice,
    this.mustChangePassword,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      name: json['name'],
      firstName: json['firstName'],
      email: json['email'],
      documentMasked: json['documentMasked'],
      documentType: json['documentType'],
      bankCompe: json['bankCompe'],
      branch: json['branch'],
      accountNumber: json['accountNumber'],
      digit: json['digit'],
      hasCompany: json['hasCompany'] ?? false,
      isCompany: json['isCompany'] ?? false,
      hasPin: json['hasPin'] ?? false,
      changeDevice: json['changeDevice'] ?? false,
      mustChangePassword: json['mustChangePassword'] ?? false,
      hierarchy: '',
    );
  }
}