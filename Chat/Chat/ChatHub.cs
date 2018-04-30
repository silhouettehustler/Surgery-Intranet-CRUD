using System.Collections.Concurrent;
using System.Collections.Generic;
using System.Linq;
using Microsoft.AspNet.SignalR;

namespace Chat
{
    public class ChatHub : Hub
    {
        private static readonly List<ChatGroup> ChatGroupsList = new List<ChatGroup>();
        private static readonly ConcurrentDictionary<string, int> ReceptionistsWaiting =
            new ConcurrentDictionary<string, int>();

        private static readonly ConcurrentDictionary<string, int>
            UsersWaiting = new ConcurrentDictionary<string, int>();

        private static readonly ConcurrentDictionary<string,int> Users = new ConcurrentDictionary<string, int>();

        public void LeaveChat()
        {
            var receptionistLeft = ChatGroupsList.SingleOrDefault(x => x.Receptionist.ConnectionId == Context.ConnectionId) != null;
            var pair = ChatGroupsList.SingleOrDefault(x => x.Patient.ConnectionId == Context.ConnectionId || x.Receptionist.ConnectionId == Context.ConnectionId);
            if (pair == null)
            {
                UsersWaiting.TryRemove(Context.ConnectionId, out var patientId);
                ReceptionistsWaiting.TryRemove(Context.ConnectionId, out var receptionistId);

                if (Users.ContainsKey(Context.ConnectionId))
                {
                    var userGroup = Users.SingleOrDefault(x => x.Key == Context.ConnectionId);
                    Groups.Remove(userGroup.Key, userGroup.Value.ToString());
                    Users.TryRemove(userGroup.Key,out var val);
                }
                return;
            }

            GlobalHost.ConnectionManager.GetHubContext<ChatHub>().Clients.Group(pair.Patient.Id.ToString()).groupTerminated();
            GlobalHost.ConnectionManager.GetHubContext<ChatHub>().Clients.Group(pair.Receptionist.Id.ToString()).groupTerminated();

            JoinChat(receptionistLeft ? pair.Patient.Id : pair.Receptionist.Id, "receptionist");

            ChatGroupsList.Remove(pair);
            
            if (Users.ContainsKey(Context.ConnectionId))
            {
                var userGroup = Users.SingleOrDefault(x => x.Key == Context.ConnectionId);
                Groups.Remove(userGroup.Key, userGroup.Value.ToString());
                Users.TryRemove(userGroup.Key,out var val);
            }
            
        }

        public void JoinChat(int userId, string tag = null)
        {
            Groups.Add(Context.ConnectionId, userId.ToString());
            Users.TryAdd(Context.ConnectionId,userId);
            //receptionist is joining chat
            var isReceptionist = !string.IsNullOrEmpty(tag);



            //create chat group
            if (isReceptionist)
            {
                if (UsersWaiting.Any())
                {
                    var user = UsersWaiting.First();
                    var group = $"{user.Value}+{userId}";
                    Groups.Add(user.Key, group);
                    Groups.Add(Context.ConnectionId, group);
                    if (ChatGroupsList.All(x => x.GroupId != group))
                    {
                        ChatGroupsList.Add(new ChatGroup { GroupId = group, Patient = new User(user.Value, user.Key, false), Receptionist = new User(userId, Context.ConnectionId, true) });
                    }
                   
                    UsersWaiting.TryRemove(user.Key, out var val);
                    GlobalHost.ConnectionManager.GetHubContext<ChatHub>().Clients.Group(group).updateClientGroup(group);

                    var pair = ChatGroupsList.SingleOrDefault(x => x.GroupId == group);
                    if (pair == null) return;
                    GlobalHost.ConnectionManager.GetHubContext<ChatHub>().Clients.Group(pair.Patient.Id.ToString()).groupEstablished(pair.Receptionist.Id);
                    GlobalHost.ConnectionManager.GetHubContext<ChatHub>().Clients.Group(pair.Receptionist.Id.ToString()).groupEstablished(pair.Patient.Id);


                }
                else
                {
                    ReceptionistsWaiting.TryAdd(Context.ConnectionId, userId);
                }
            }
            else
            {
                if (ReceptionistsWaiting.Any())
                {
                    var receptionist = ReceptionistsWaiting.First();
                    var group = $"{userId}+{receptionist.Value}";
                    Groups.Add(receptionist.Key, group);
                    Groups.Add(Context.ConnectionId, group);

                    if (ChatGroupsList.All(x => x.GroupId != group))
                    {
                        ChatGroupsList.Add(new ChatGroup { GroupId = group, Patient = new User(userId, Context.ConnectionId, false), Receptionist = new User(receptionist.Value, receptionist.Key, true) });
                    }

                    ReceptionistsWaiting.TryRemove(receptionist.Key, out var val);
                    GlobalHost.ConnectionManager.GetHubContext<ChatHub>().Clients.Group(group).updateClientGroup(group);

                    var pair = ChatGroupsList.SingleOrDefault(x => x.GroupId == group);
                    if (pair == null) return;
                    GlobalHost.ConnectionManager.GetHubContext<ChatHub>().Clients.Group(pair.Patient.Id.ToString()).groupEstablished(pair.Receptionist.Id);
                    GlobalHost.ConnectionManager.GetHubContext<ChatHub>().Clients.Group(pair.Receptionist.Id.ToString()).groupEstablished(pair.Patient.Id);

                }
                else
                {
                    UsersWaiting.TryAdd(Context.ConnectionId, userId);
                }
            }
        }

        public void Send(string message, string name, string groupid)
        {
            GlobalHost.ConnectionManager.GetHubContext<ChatHub>().Clients.Group(groupid, Context.ConnectionId).addNewMessageToPage(message, name);
        }

        private enum ChatStatus
        {
            Initiating = 1,
            Started = 2,
            Ended = 3
        }

        public class User
        {
            public int Id { get; set; }
            public string ConnectionId { get; set; }
            public bool IsReceptionist { get; set; }

            public User(int id, string connectionId, bool isReceptionist)
            {
                Id = id;
                ConnectionId = connectionId;
                IsReceptionist = isReceptionist;
            }
        }

        public class ChatGroup
        {
            public User Patient { get; set; }
            public User Receptionist { get; set; }
            public string GroupId { get; set; }
        }
    }
}