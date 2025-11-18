import 'package:flutter/material.dart';
import 'package:pluggo/src/domain/value_object/value.dart';

class ProductGlobalItem {
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

  ProductGlobalItem({
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

  factory ProductGlobalItem.fromJson(Map<String, dynamic> json) {
    return ProductGlobalItem(
      id: json['id'],
      userId: json['userId'],
      name: json['name'],
      value: Value(json['value'].toString()),
      estimateWasteDays: json['estimateWasteDays'],
      lastPurchaseDate: json['lastPurchaseDate'],
      reminderDate: json['reminderDate'],
      calendarEventId: json['calendarEventId'],
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

class ProductDetailProvider extends ChangeNotifier {
  ProductGlobalItem? product;

  void setProduct(ProductGlobalItem newProduct) {
    product = newProduct;
    notifyListeners();
  }

  void clearProduct() {
    product = null;
    notifyListeners();
  }
}


class ProductGlobalProvider extends ChangeNotifier {
  List<ProductGlobalItem> products = [];

  void setProductsFromList(List<dynamic> jsonList) {
    products = jsonList
        .map((item) => ProductGlobalItem.fromJson(item as Map<String, dynamic>))
        .toList();
    notifyListeners();
  }

  void reset() {
    products = [];
    notifyListeners();
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
      userName: json['userName']??'desconhecido',
      createdAt: json['createdAt'],
      text: json['text'],
    );
  }
}
