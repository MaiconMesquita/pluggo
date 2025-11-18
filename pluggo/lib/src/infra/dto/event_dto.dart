class GoogleCalendarEventDto {
  final String summary;
  final String description;
  final DateTime startDateTime;
  final DateTime endDateTime;
  final String timeZone;

  GoogleCalendarEventDto({
    required this.summary,
    required this.description,
    required this.startDateTime,
    required this.endDateTime,
    this.timeZone = 'America/Sao_Paulo',
  });

  Map<String, dynamic> toJson() {
    return {
      'summary': summary,
      'description': description,
      'start': {
        'dateTime': startDateTime.toIso8601String(),
        'timeZone': timeZone,
      },
      'end': {
        'dateTime': endDateTime.toIso8601String(),
        'timeZone': timeZone,
      },
      "recurrence": [
        "RRULE:FREQ=MONTHLY"
      ],
      "reminders": {
        "useDefault": false,
        "overrides": [
          {
            "method": "popup",
            "minutes": 1
          }
        ]
      }
    };
  }
}
