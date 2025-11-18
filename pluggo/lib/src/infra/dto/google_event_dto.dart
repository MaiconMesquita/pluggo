import 'package:flutter/material.dart';

class GoogleEvent {
  final String id;
  final String etag;
  final String status;
  final String htmlLink;
  final DateTime created;
  final DateTime updated;
  final String summary;
  final String description;
  final Creator creator;
  final Organizer organizer;
  final DateTime startDateTime;
  final DateTime endDateTime;
  final List<String> recurrence;
  final String iCalUID;
  final int sequence;
  final Reminders reminders;
  final String eventType;

  GoogleEvent({
    required this.id,
    required this.etag,
    required this.status,
    required this.htmlLink,
    required this.created,
    required this.updated,
    required this.summary,
    required this.description,
    required this.creator,
    required this.organizer,
    required this.startDateTime,
    required this.endDateTime,
    required this.recurrence,
    required this.iCalUID,
    required this.sequence,
    required this.reminders,
    required this.eventType,
  });

  factory GoogleEvent.fromJson(Map<String, dynamic> json) {
    return GoogleEvent(
      id: json['id'] ?? '',
      etag: json['etag'] ?? '',
      status: json['status'] ?? '',
      htmlLink: json['htmlLink'] ?? '',
      created: DateTime.parse(json['created'] ?? ''),
      updated: DateTime.parse(json['updated'] ?? ''),
      summary: json['summary'] ?? '',
      description: json['description'] ?? '',
      creator: Creator.fromJson(json['creator']),
      organizer: Organizer.fromJson(json['organizer']),
      startDateTime: DateTime.parse(json['start']['dateTime'] ?? ''),
      endDateTime: DateTime.parse(json['end']['dateTime'] ?? ''),
      recurrence: List<String>.from(json['recurrence'] ?? []),
      iCalUID: json['iCalUID'] ?? '',
      sequence: json['sequence'] ?? 0,
      reminders: Reminders.fromJson(json['reminders']),
      eventType: json['eventType'] ?? '',
    );
  }
}

class Creator {
  final String email;
  final bool self;

  Creator({required this.email, required this.self});

  factory Creator.fromJson(Map<String, dynamic> json) {
    return Creator(
      email: json['email'] ?? '',
      self: json['self'] ?? false,
    );
  }
}

class Organizer {
  final String email;
  final bool self;

  Organizer({required this.email, required this.self});

  factory Organizer.fromJson(Map<String, dynamic> json) {
    return Organizer(
      email: json['email'] ?? '',
      self: json['self'] ?? false,
    );
  }
}

class Reminders {
  final bool useDefault;
  final List<Override> overrides;

  Reminders({required this.useDefault, required this.overrides});

  factory Reminders.fromJson(Map<String, dynamic> json) {
    return Reminders(
      useDefault: json['useDefault'] ?? false,
      overrides: (json['overrides'] as List<dynamic>? ?? [])
          .map((item) => Override.fromJson(item))
          .toList(),
    );
  }
}

class Override {
  final String method;
  final int minutes;

  Override({required this.method, required this.minutes});

  factory Override.fromJson(Map<String, dynamic> json) {
    return Override(
      method: json['method'] ?? '',
      minutes: json['minutes'] ?? 0,
    );
  }
}

class GoogleEventProvider extends ChangeNotifier {
  GoogleEvent? googleEvent;

  void setGoogleEvent(Map<String, dynamic> json) {
    googleEvent = GoogleEvent.fromJson(json);
    notifyListeners();
  }

  void reset() {
    googleEvent = null;
    notifyListeners();
  }
}
