using System.Collections.Concurrent;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNet.SignalR;

namespace Chat
{
    public class ChatHub : Hub
    {
        private static readonly ConcurrentDictionary<string, int> ReceptionistsWaiting =
            new ConcurrentDictionary<string, int>();

        private static readonly ConcurrentDictionary<string, int>
            UsersWaiting = new ConcurrentDictionary<string, int>();

        public override Task OnConnected()
        {
            return base.OnConnected();
        }

        public override Task OnDisconnected(bool stopCalled)
        {
            return base.OnDisconnected(stopCalled);
        }

        public void JoinChat(int userId, string tag = null)
        {
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
                    UsersWaiting.TryRemove(user.Key, out var val);
                    GlobalHost.ConnectionManager.GetHubContext<ChatHub>().Clients.Group(group).updateClientGroup(group);
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
                    ReceptionistsWaiting.TryRemove(receptionist.Key, out var val);
                    GlobalHost.ConnectionManager.GetHubContext<ChatHub>().Clients.Group(group).updateClientGroup(group);
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
    }
}