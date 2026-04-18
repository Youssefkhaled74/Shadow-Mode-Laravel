import { onBeforeUnmount, ref } from 'vue';

export function useRoomRealtime(sessionUuid) {
    const members = ref([]);
    const activity = ref([]);
    const hints = ref([]);
    const latestMetrics = ref(null);
    const state = ref(null);

    const channelName = `shadow.room.${sessionUuid}`;
    let channel = null;

    if (window.Echo && sessionUuid) {
        channel = window.Echo.join(channelName)
            .here((users) => {
                members.value = users;
            })
            .joining((user) => {
                members.value = [...members.value, user];
            })
            .leaving((user) => {
                members.value = members.value.filter((member) => member.id !== user.id);
            })
            .listen('.session.activity.logged', (payload) => {
                activity.value = [payload, ...activity.value].slice(0, 30);
            })
            .listen('.coaching.hint.published', (payload) => {
                hints.value = [payload, ...hints.value].slice(0, 30);
            })
            .listen('.metrics.updated', (payload) => {
                latestMetrics.value = payload;
            })
            .listen('.session.state.updated', (payload) => {
                state.value = payload.state;
            });
    }

    onBeforeUnmount(() => {
        if (window.Echo && channelName) {
            window.Echo.leave(channelName);
        }
    });

    return {
        members,
        activity,
        hints,
        latestMetrics,
        state,
    };
}

