class NewProduct {
  final String userId;
  final String name;
  final double value;
  final int estimateWasteDays;
  final DateTime lastPurchaseDate;
  final String calendarEventId;
  final bool globalStock;

  NewProduct({
    required this.userId,
    required this.name,
    required this.value,
    required this.estimateWasteDays,
    required this.lastPurchaseDate,
    required this.calendarEventId,
    required this.globalStock,
  });

  Map<String, dynamic> toJson() {
    return {
      'userId': userId,
      'name': name,
      'value': value,
      'estimateWasteDays': estimateWasteDays,
      'lastPurchaseDate': lastPurchaseDate.toIso8601String().split('T').first,
      'calendarEventId': calendarEventId,
      'globalStock': globalStock,
    };
  }
}
