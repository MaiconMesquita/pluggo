import 'package:flutter/material.dart';
import 'package:pluggo/src/domain/value_object/value.dart';


class ProductResponse {
  final int total;
  final List<ProductItem> products;

  ProductResponse({
    required this.total,
    required this.products,
  });

  factory ProductResponse.fromJson(Map<String, dynamic> json) {
    return ProductResponse(
      total: json['total'] ?? 0,
      products: (json['products'] as List<dynamic>)
          .map((item) => ProductItem.fromJson(item))
          .toList(),
    );
  }
}

class ProductItem {
  final int id;
  final String userId;
  final String name;
  final Value value;
  final int estimateWasteDays;
  final String lastPurchaseDate;
  final String reminderDate;
  final String calendarEventId;
  final String creationTimestamp;
  final bool globalStock;
  final String addedBy;
  final bool activeStatus;
  final int users;
  final List<Comment> comments;

  ProductItem({
    required this.id,
    required this.userId,
    required this.name,
    required this.value,
    required this.estimateWasteDays,
    required this.lastPurchaseDate,
    required this.reminderDate,
    required this.calendarEventId,
    required this.creationTimestamp,
    required this.globalStock,
    required this.addedBy,
    required this.activeStatus,
    required this.users,
    required this.comments,
  });

  factory ProductItem.fromJson(Map<String, dynamic> json) {
    return ProductItem(
      id: json['id']?? '',
      userId: json['userId']?? '',
      name: json['name']?? '',
      value: Value(json['value'].toString()),
      estimateWasteDays: json['estimateWasteDays']?? '',
      lastPurchaseDate: json['lastPurchaseDate']?? '',
      reminderDate: json['reminderDate']?? '',
      calendarEventId: json['calendarEventId']?? '',
      creationTimestamp: json['creationTimestamp'],
      globalStock: json['globalStock'] ?? false,
      addedBy: json['addedBy'] ?? '',
      activeStatus: json['activeStatus'] ?? false,
      users: json['users'] ?? 0,
      comments: (json['comments'] as List<dynamic>?)
          ?.map((e) => Comment.fromJson(e))
          .toList() ??
          [],
    );
  }
}

class Comment {
  final int id;
  final String userId;
  final String userName;
  final String createdAt;
  final String text;

  Comment({
    required this.id,
    required this.userId,
    required this.userName,
    required this.createdAt,
    required this.text,
  });

  factory Comment.fromJson(Map<String, dynamic> json) {
    return Comment(
      id: json['id'],
      userId: json['userId'],
      userName: json['userName'],
      createdAt: json['createdAt'],
      text: json['text'],
    );
  }
}
class ProductProvider extends ChangeNotifier {
  List<ProductItem> products = [];
  int total = 0;

  void setProductsFromResponse(ProductResponse response) {
    products = response.products;
    total = response.total;
    notifyListeners();
  }

  void reset() {
    products = [];
    total = 0;
    notifyListeners();
  }
}

