export const deleteEntity = async ({
    action,
    csrfToken,
    entityName,
  }: {
    action: string;
    csrfToken: string;
    entityName: string;
  }): Promise<void> => {
    const res = await fetch(action, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        _method: 'DELETE',
        _csrf_token: csrfToken,
      }),
    });
  
    if (!res.ok) {
      throw new Error(`Échec de la suppression de ${entityName} (${res.status})`);
    }
  };  