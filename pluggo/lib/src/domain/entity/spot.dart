class Spot {
  final int id;
  final int hostId;
  final String name;
  final double latitude;
  final double longitude;
  final String status;

  Spot({
    required this.id,
    required this.hostId,
    required this.name,
    required this.latitude,
    required this.longitude,
    required this.status,
  });

  factory Spot.fromJson(Map<String, dynamic> json) {
    return Spot(
      id: json['id'] as int,
      hostId: json['hostId'] as int,
      name: json['name'] ?? '',
      latitude: double.parse(json['latitude']),
      longitude: double.parse(json['longitude']),
      status: json['status'] ?? '',
    );
  }
}
