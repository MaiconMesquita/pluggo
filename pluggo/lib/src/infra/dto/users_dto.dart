import 'package:flutter/material.dart';

class UsersResponse {
  final List<UserItem> users;

  UsersResponse({required this.users});

  factory UsersResponse.fromJson(List<dynamic> jsonList) {
    return UsersResponse(
      users: jsonList.map((item) => UserItem.fromJson(item)).toList(),
    );
  }
}

class UserItem {
  final String userId;
  final String username;
  final String email;
  final String creationTimestamp;
  final String updateTimestamp;

  UserItem({
    required this.userId,
    required this.username,
    required this.email,
    required this.creationTimestamp,
    required this.updateTimestamp,
  });

  factory UserItem.fromJson(Map<String, dynamic> json) {
    return UserItem(
      userId: json['userId'] ?? '',
      username: json['username'] ?? '',
      email: json['email'] ?? '',
      creationTimestamp: json['creationTimestamp'] ?? '',
      updateTimestamp: json['updateTimestamp'] ?? '',
    );
  }
}

class UsersProvider extends ChangeNotifier {
  List<UserItem> users = [];

  void setUsersFromResponse(UsersResponse response) {
    users = response.users;
    notifyListeners();
  }

  void reset() {
    users = [];
    notifyListeners();
  }
}
