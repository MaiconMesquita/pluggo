class LoginDto {
  final String userId;

  LoginDto({
    required this.userId
  });

  // Método para converter JSON para AuthDto
  factory LoginDto.fromJson(Map<String, dynamic> json) {
    return LoginDto(
      userId: json['userId'] ?? '',
    );
  }

  // Método para converter AuthDto para JSON, se necessário
  Map<String, dynamic> toJson() {
    return {
      'userId': userId,
    };
  }
}
