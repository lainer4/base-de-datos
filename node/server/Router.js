

var User = require('./models').User,
    EventModel = require('./models').EventModel,
    Router = require('express').Router(),
    passwordHash = require('password-hash'),
    moment = require('moment');



Router.post('/event/', function(req, res) {

  let response = {};

  User.findOne({username: req.session.username}, function(err, user) {
    if (err) {
      response.message = "500";
      response.description = "The user was not found...";
    }

    let id = user._id,
    newEvent;

    let title = req.body.title,
    startDate = req.body.startDate,
    startHour = req.body.startHour,
    endDate = req.body.endDate,
    endHour = req.body.endHour,
    fullDay = req.body.fullDay;

    newEvent = new EventModel({
      userId: id,
      title: title,
      startDate: startDate,
      startHour: startHour,
      endDate: endDate,
      endHour: endHour,
      fullDay: fullDay
    });

    newEvent.save(function(err) {
      if (err) {
        response.message = '500';
        response.description = 'The post could not be saved...';
      } else {
        response.message = 'OK';
        response.description = 'The post was successfully saved!';
      }

      res.json(response);
    });
  });
})

.delete('/event/:id', function(req, res) {

  let response = {};

  User.findOne({username: req.session.username}, function(err, user) {

    if (err) {
      response.message = '500';
      response.description = 'The user could not be found...';
    }

    let id = user._id;

    EventModel.findOneAndRemove({userId: id, _id: req.params.id}, function(err, myEvent) {

    if (err) {
      response.message = '500';
      response.description = 'The event could not be deleted...';
    } else {
      response.message = 'OK';
      response.description = 'The event was successfully deleted!';
    }

      res.json(response);
    });

  });
}) 



.put('/event/:id', function(req, res) {
  let response = {};

  User.findOne({username: req.session.username}, function(err, user) {

    var id = user._id;

    EventModel.findOne({userId: id, _id: req.params.id}, function(err, myEvent) {

      if (err) {
        response.message = '500';
        response.description = 'The event could not be found...';
      }

      myEvent.startDate = req.body.startDate;
      myEvent.endDate = req.body.endDate;
      myEvent.startHour = req.body.startHour;
      myEvent.endHour = req.body.endHour;
      myEvent.fullDay = req.body.fullDay;

      myEvent.save(function(err) {

        if (err) {
          response.message = '500';
          response.description = 'The event could not be updated...';
        } else {
          response.message = 'OK';
          response.description = 'The event was successfully updated!';
        }

        res.json(response);
      });
    });
  });
}) 



.post('/login/', function(req, res) {

  let response = {};

  User.findOne({username: req.body.user}, function(err, user) {
    
    if (!user) {
      response.message = '500';
      response.description = 'The inserted email was not found in our database...';
    
    } else {
      if (passwordHash.verify(req.body.pass, user.password)) {
        req.session.username = req.body.user;
        response.message = 'OK';
        response.description = 'You have successfully logged in!';
       
      } else {
        req.session.destroy(function(err) {});
        response.message = '500';
        response.description = 'The password was incorrect...';
        
      }
    }

    res.json(response);
  });
}) 



.get('/login/', function(req, res) {
  let response = {};

  if (req.session.username) {
    response.message = 'OK';
  } else {
    response.message = '500';
    response.description = 'You must login first!';
  }

  res.json(response);
}) 

.get('/logout', function(req, res) {

  let response = {};

  req.session.destroy(function(err) {
    if (err) {
      response.message = '500';
      response.description = 'Impossible to log out at the moment...';
    } else {
      response.message = 'OK';
      response.description = 'You have successfully logged out!';
    }

    res.json(response);
  });
}) 



.get('/events/', function(req, res) {

  let response = {};

    User.findOne({username: req.session.username}, function(err, user) {

      var id = user._id;

      EventModel.find({userId: id}, function(err, events) {
        if (err) {
          response.message = '500';
          response.description = 'The events could not be fetched...';
        } else {
          response.message = 'OK';
          response.events = prepareEvents(events);
        }
        res.json(response);
      });
    });

});



function prepareEvents(events) {
  let preparedEvents = [];

  events.forEach(function(event, index) {
    let preparedEvent = {};
    preparedEvent.id = event._id;
    preparedEvent.title = event.title;
    preparedEvent.fullDay = event.fullDay;
    if (preparedEvent.fullDay) {
      if (event.startHour != '' && event.startHour != '00:00:00') {
        let startDateTime = new moment(event.startDate + ' ' + event.startHour).format("YYYY-MM-DD HH:mm:ss");

        preparedEvent.start = startDateTime;
      } else {
        preparedEvent.start = event.startDate;
      }

    } else {
      let startDateTime = new moment(event.startDate + ' ' + event.startHour).format("YYYY-MM-DD HH:mm:ss");

      let endDateTime = new moment(event.endDate + ' ' + event.endHour).format("YYYY-MM-DD HH:mm:ss");

      preparedEvent.start = startDateTime;
      preparedEvent.end = endDateTime;
    }
    preparedEvents.push(preparedEvent);
  });
  return preparedEvents;
} 

exports.Router = Router;
