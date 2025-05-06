import React from 'react';
import Profile from './Profile';
import Statistics from './Statistics';
import RecentActivity from './RecentActivity';
import Notifications from './Notifications';

export default function Dashboard() {
    return (
        <div className="container mx-auto px-4 py-8">
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <Statistics />
                <RecentActivity />
                <Notifications />
            </div>
            <Profile />
        </div>
    );
}