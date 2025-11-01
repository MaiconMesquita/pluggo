

class ProfileDTO {
  final UserProfile userProfile;

  ProfileDTO({required this.userProfile});

  factory ProfileDTO.fromJson(Map<String, dynamic> json) {
    return ProfileDTO(
      userProfile: UserProfile.fromJson(json['user']),
    );
  }
}

class UserProfile {
  final int id;
  final String userType;
  final String name;
  final String rg;
  final String? birthDate;
  final String? gender;
  final String email;
  final String? fatherName;
  final String? motherName;
  final int? dueDay;
  final int? closingDay;
  final bool ccbStatus;
  final String? street;
  final String? number;
  final String? complement;
  final String? neighborhood;
  final String? city;
  final String? state;
  final String? postalCode;
  final bool status;
  final bool changePassword;
  final bool codeValidation;
  final String deviceId;
  final bool acceptedTermsOfUse;
  final bool acceptedCardTerms;
  final double? latitude;
  final double? longitude;
  final String? deactivationDate;
  final String createdAt;
  final String updatedAt;

  UserProfile({
    required this.id,
    required this.userType,
    required this.name,
    required this.rg,
    this.birthDate,
    this.gender,
    required this.email,
    this.fatherName,
    this.motherName,
    this.dueDay,
    this.closingDay,
    required this.ccbStatus,
    this.street,
    this.number,
    this.complement,
    this.neighborhood,
    this.city,
    this.state,
    this.postalCode,
    required this.status,
    required this.changePassword,
    required this.codeValidation,
    required this.deviceId,
    required this.acceptedTermsOfUse,
    required this.acceptedCardTerms,
    this.latitude,
    this.longitude,
    this.deactivationDate,
    required this.createdAt,
    required this.updatedAt,
  });

  factory UserProfile.fromJson(Map<String, dynamic> json) {
    return UserProfile(
      id: json['id'] ?? 0,
      userType: json['userType'] ?? '',
      name: json['name'] ?? '',
      rg: json['rg'] ?? '',
      birthDate: json['birthDate'], // Pode ser null
      gender: json['gender'], // Pode ser null
      email: json['email'] ?? '',
      fatherName: json['fatherName'], // Pode ser null
      motherName: json['motherName'], // Pode ser null
      dueDay: json['dueDay'] , // Pode ser null
      closingDay: json['closingDay'], // Pode ser null
      ccbStatus: json['CCBStatus'] ?? false,
      street: json['street'],
      number: json['number'],
      complement: json['complement'], // Pode ser null
      neighborhood: json['neighborhood'],
      city: json['city'],
      state: json['state'],
      postalCode: json['postalCode'],
      status: json['status'] ?? false,
      changePassword: json['changePassword'] ?? false,
      codeValidation: json['codeValidation'] ?? false,
      deviceId: json['deviceId'] ?? '',
      acceptedTermsOfUse: json['acceptedTermsOfUse'] ?? false,
      acceptedCardTerms: json['acceptedCardTerms'] ?? false,
      latitude: json['latitude'] != null
          ? double.tryParse(json['latitude'])
          : null, // Garantindo conversão para double
      longitude: json['longitude'] != null
          ? double.tryParse(json['longitude'])
          : null, // Garantindo conversão para double
      deactivationDate: json['deactivationDate'], // Pode ser null
      createdAt: json['createdAt'] ?? '',
      updatedAt: json['updatedAt'] ?? '',
    );
  }
}
