import React, { useEffect, useState } from 'react';
import UserPage from './userPage';
import ExperiencePage from './experiencePage';
import TrainingPage from './trainingPage';
import BlockPage from './blockPage';

type HomePageProps = {
  user: any;
  experiences: any;
  trainings: any;
  blocks: any;
  editUserAction: string;
  csrfTokens: {
    user: string;
    experience: string;
    training: string;
    block: string;
  };
};

export default function HomePage({
  user,
  experiences,
  trainings,
  blocks,
  csrfTokens,
  editUserAction
}: HomePageProps) {
  const [isReady, setIsReady] = useState(false);

  useEffect(() => {
    setIsReady(true);
  }, []);

  if (!isReady) {
    return (
      <div className="flex items-center justify-center min-h-screen">
        <div className="border-4 border-gray-300 border-t-orange-500 rounded-full w-12 h-12 animate-spin" />
      </div>
    );
  }

  return (
    <div className="flex-1 w-full py-8 px-2 sm:px-4 lg:px-0">
        <div className="max-w-6xl mx-auto border border-gray-300 bg-white rounded-2xl p-6 lg:p-8 section-background">
            <div className="flex flex-col gap-8">
            <UserPage editUserAction={editUserAction} user={user} csrfToken={csrfTokens.user} />

            <div className="flex flex-col lg:flex-row gap-8">
                <div className="flex flex-col gap-8 w-full lg:w-2/3">
                <ExperiencePage experiences={experiences} csrfToken={csrfTokens.experience} />
                <TrainingPage trainings={trainings} csrfToken={csrfTokens.training} />
                </div>

                <aside className="w-full lg:w-1/3 max-w-md">
                <BlockPage blocks={blocks} csrfToken={csrfTokens.block} />
                </aside>
            </div>
            </div>
        </div>
    </div>
  );
}